# 🚀 ROI Labs - Link Page

Uma página de links moderna e responsiva para a ROI Labs, construída com React e animações avançadas.

## ✨ Características

- **Design Moderno**: Interface glassmorphism com gradientes dinâmicos
- **Responsivo**: Funciona perfeitamente em desktop, tablet e mobile
- **Animações Suaves**: Efeitos visuais envolventes e interativos
- **Performance Otimizada**: Carregamento rápido e animações fluidas
- **SEO Friendly**: Meta tags otimizadas para redes sociais
- **Analytics Ready**: Preparado para Google Analytics e tracking

## 🛠️ Tecnologias

- **React 18**: Biblioteca JavaScript para interface
- **CSS3**: Estilos modernos com animações e glassmorphism
- **Tailwind-like Classes**: Sistema de classes utilitárias
- **Mobile First**: Design responsivo prioritário

## 📁 Estrutura do Projeto

```
roi-labs-links/
├── index.html              # Página principal
├── assets/
│   ├── css/
│   │   └── roi-labs-links.css    # Estilos otimizados
│   └── js/
│       └── roi-labs-links.js     # Código React
├── README.md               # Documentação
└── version.json           # Controle de versão
```

## 🌐 URLs de Acesso

### GitHub Pages (Principal)
```
https://jeanzorzetti.github.io/roi-labs-links/
```

### CDN Direto (jsDelivr)
```
https://cdn.jsdelivr.net/gh/JeanZorzetti/roi-labs-links@main/index.html
```

### Raw GitHub (Desenvolvimento)
```
https://raw.githubusercontent.com/JeanZorzetti/roi-labs-links/main/index.html
```

## 🔗 Integração WordPress

### Método 1: iFrame (Mais Simples)
```html
<iframe 
    src="https://jeanzorzetti.github.io/roi-labs-links/" 
    width="100%" 
    height="800" 
    frameborder="0"
    style="border: none; border-radius: 1rem;">
</iframe>
```

### Método 2: Código Direto no Elementor
```html
<!-- No widget HTML do Elementor -->
<div id="roi-labs-root" style="min-height: 100vh;"></div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/JeanZorzetti/roi-labs-links@main/assets/css/roi-labs-links.css">

<script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

<script type="text/babel" src="https://cdn.jsdelivr.net/gh/JeanZorzetti/roi-labs-links@main/assets/js/roi-labs-links.js"></script>
```

### Método 3: Plugin WordPress (Avançado)
Plugin customizado disponível em `/wordpress-plugin/` que permite:
- Shortcode `[roi-labs-links]`
- Widget no admin
- Configurações personalizáveis
- Cache otimizado

## 📊 Analytics e Tracking

O sistema inclui:
- Tracking automático de cliques
- Integração com Google Analytics
- Labels personalizadas para cada link
- Eventos customizados para conversão

```javascript
// Exemplo de evento tracking
gtag('event', 'click', {
  event_category: 'bio_link',
  event_label: 'auditoria_gratuita',
});
```

## 🎨 Personalização

### Alterando Links
Edite o array `linkData` em `assets/js/roi-labs-links.js`:

```javascript
const linkData = [
  {
    id: 'novo_link',
    icon: '🎯',
    title: 'Novo Título',
    description: 'Nova descrição',
    url: 'https://seu-link.com',
    color: 'cyan',
  }
];
```

### Customizando Cores
Modifique as variáveis CSS em `assets/css/roi-labs-links.css`:

```css
/* Cores principais */
--primary-cyan: #22d3ee;
--primary-blue: #60a5fa;
--primary-purple: #c084fc;
```

## 🚀 Deploy e Atualizações

### Automático (GitHub Actions)
Qualquer push para `main` atualiza automaticamente:
- GitHub Pages
- CDN jsDelivr
- Cache invalidation

### Manual
1. Edite os arquivos necessários
2. Commit e push para `main`
3. Aguarde 1-2 minutos para propagação do CDN

## 🔧 Desenvolvimento Local

```bash
# Clone o repositório
git clone https://github.com/JeanZorzetti/roi-labs-links.git

# Navegue para o diretório
cd roi-labs-links

# Sirva localmente (qualquer servidor HTTP)
python -m http.server 8000
# ou
npx serve .
```

Acesse: `http://localhost:8000`

## 📱 Responsividade

A página é otimizada para:
- **Desktop**: 1200px+ (layout completo)
- **Tablet**: 768px-1199px (layout adaptado)
- **Mobile**: <768px (layout mobile-first)

## ⚡ Performance

- **Primeira Carga**: <2s
- **Lighthouse Score**: 95+
- **Mobile Friendly**: ✅
- **Core Web Vitals**: Otimizado

## 🔒 Segurança

- HTTPS obrigatório
- Content Security Policy compatível
- XSS protection
- Sanitização de URLs

## 📈 Métricas

Para monitorar performance:
1. Google Analytics configurado
2. Search Console integrado
3. Core Web Vitals tracking
4. Conversion tracking

## 🆘 Suporte

Para suporte técnico:
- **Issues**: GitHub Issues
- **WhatsApp**: +55 62 98110-9211
- **Email**: contato@roilabs.com.br

## 📜 Licença

© 2025 ROI Labs. Todos os direitos reservados.

---

**Última atualização**: Junho 2025
**Versão**: 1.0.0
**Status**: ✅ Produção