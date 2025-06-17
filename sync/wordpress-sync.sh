#!/bin/bash

# ROI Labs GitHub → WordPress Sync Script
# Este script sincroniza automaticamente os arquivos do GitHub com o WordPress

set -e

# Configurações
GITHUB_REPO="https://raw.githubusercontent.com/JeanZorzetti/roi-labs-links/main"
WORDPRESS_PATH="/var/lib/docker/volumes/sites_wordpress_data/_data"
PLUGIN_PATH="$WORDPRESS_PATH/wp-content/plugins/roi-labs-links"
LOG_FILE="/tmp/roi-labs-sync.log"

# Função de log
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" | tee -a "$LOG_FILE"
}

# Função para baixar arquivo do GitHub
download_file() {
    local url="$1"
    local dest="$2"
    
    log "📥 Baixando: $url → $dest"
    
    if curl -sSL "$url" -o "$dest.tmp"; then
        mv "$dest.tmp" "$dest"
        log "✅ Download concluído: $dest"
        return 0
    else
        log "❌ Erro no download: $url"
        rm -f "$dest.tmp"
        return 1
    fi
}

# Função para verificar se há atualizações
check_updates() {
    local version_url="$GITHUB_REPO/version.json"
    local current_version_file="$PLUGIN_PATH/current_version.json"
    
    log "🔍 Verificando atualizações..."
    
    # Baixar versão atual do GitHub
    if ! curl -sSL "$version_url" -o "/tmp/github_version.json"; then
        log "❌ Erro ao verificar versão no GitHub"
        return 1
    fi
    
    # Comparar versões se arquivo local existe
    if [ -f "$current_version_file" ]; then
        local current_build=$(jq -r '.build // "0"' "$current_version_file" 2>/dev/null || echo "0")
        local github_build=$(jq -r '.build // "0"' "/tmp/github_version.json" 2>/dev/null || echo "0")
        
        if [ "$current_build" = "$github_build" ]; then
            log "✅ Versão já atualizada ($github_build)"
            return 1
        fi
        
        log "🆕 Nova versão disponível: $current_build → $github_build"
    else
        log "🆕 Primeira instalação detectada"
    fi
    
    return 0
}

# Função principal de sincronização
sync_files() {
    log "🚀 Iniciando sincronização ROI Labs Links"
    
    # Criar diretórios se não existirem
    mkdir -p "$PLUGIN_PATH"
    mkdir -p "$PLUGIN_PATH/assets/css"
    mkdir -p "$PLUGIN_PATH/assets/js"
    
    # Lista de arquivos para sincronizar
    declare -A files=(
        ["index.html"]="$PLUGIN_PATH/index.html"
        ["assets/css/roi-labs-links.css"]="$PLUGIN_PATH/assets/css/roi-labs-links.css"
        ["assets/js/roi-labs-links.js"]="$PLUGIN_PATH/assets/js/roi-labs-links.js"
        ["wordpress-plugin/roi-labs-links.php"]="$PLUGIN_PATH/roi-labs-links.php"
        ["wordpress-plugin/elementor-widget.php"]="$PLUGIN_PATH/elementor-widget.php"
        ["version.json"]="$PLUGIN_PATH/current_version.json"
        ["README.md"]="$PLUGIN_PATH/README.md"
    )
    
    local success_count=0
    local total_count=${#files[@]}
    
    # Sincronizar cada arquivo
    for github_path in "${!files[@]}"; do
        local local_path="${files[$github_path]}"
        local github_url="$GITHUB_REPO/$github_path"
        
        if download_file "$github_url" "$local_path"; then
            ((success_count++))
        fi
    done
    
    # Ajustar permissões
    chown -R www-data:www-data "$PLUGIN_PATH"
    chmod -R 755 "$PLUGIN_PATH"
    
    log "📊 Sincronização concluída: $success_count/$total_count arquivos"
    
    # Ativar plugin se necessário
    if [ -f "$PLUGIN_PATH/roi-labs-links.php" ]; then
        log "🔌 Plugin WordPress disponível em: $PLUGIN_PATH/roi-labs-links.php"
    fi
    
    return 0
}

# Função para invalidar cache
clear_cache() {
    log "🧹 Limpando cache..."
    
    # Cache do WordPress
    if [ -d "$WORDPRESS_PATH/wp-content/cache" ]; then
        rm -rf "$WORDPRESS_PATH/wp-content/cache/*"
        log "✅ Cache WordPress limpo"
    fi
    
    # Cache do Elementor
    if [ -d "$WORDPRESS_PATH/wp-content/uploads/elementor/css" ]; then
        rm -rf "$WORDPRESS_PATH/wp-content/uploads/elementor/css/*"
        log "✅ Cache Elementor limpo"
    fi
    
    # Restart do container WordPress para garantir
    log "🔄 Reiniciando container WordPress..."
    docker restart sites_wordpress.1.bwhi9ffoy9fqv6d37rwhfs49f || log "⚠️ Erro ao reiniciar container"
    
    return 0
}

# Função de notificação
notify_completion() {
    local status="$1"
    local message="$2"
    
    # Log local
    log "$message"
    
    # Webhook de notificação (opcional)
    if [ -n "$WEBHOOK_URL" ]; then
        curl -sSL -X POST "$WEBHOOK_URL" \
            -H "Content-Type: application/json" \
            -d "{\"status\":\"$status\",\"message\":\"$message\",\"timestamp\":\"$(date -u +%Y-%m-%dT%H:%M:%SZ)\"}" \
            >/dev/null 2>&1 || true
    fi
}

# Função principal
main() {
    log "🎯 ROI Labs GitHub Sync v1.0.0"
    
    # Verificar dependências
    if ! command -v curl >/dev/null 2>&1; then
        log "❌ curl não encontrado"
        exit 1
    fi
    
    if ! command -v jq >/dev/null 2>&1; then
        log "⚠️ jq não encontrado, instalando..."
        apt-get update >/dev/null 2>&1 && apt-get install -y jq >/dev/null 2>&1 || {
            log "❌ Erro ao instalar jq"
            exit 1
        }
    fi
    
    # Verificar se há atualizações
    if [ "$1" != "--force" ] && ! check_updates; then
        log "✅ Nenhuma atualização necessária"
        exit 0
    fi
    
    # Executar sincronização
    if sync_files; then
        clear_cache
        notify_completion "success" "✅ Sincronização ROI Labs concluída com sucesso"
    else
        notify_completion "error" "❌ Erro na sincronização ROI Labs"
        exit 1
    fi
}

# Executar apenas se chamado diretamente
if [ "${BASH_SOURCE[0]}" = "${0}" ]; then
    main "$@"
fi