const { useState, useEffect, useRef } = React;

const ROILabsLinkPage = () => {
  const [isLoaded, setIsLoaded] = useState(false);
  const [mousePosition, setMousePosition] = useState({ x: 0, y: 0 });
  const [hoveredCard, setHoveredCard] = useState(null);
  const containerRef = useRef(null);

  useEffect(() => {
    const timer = setTimeout(() => setIsLoaded(true), 1000);
    return () => clearTimeout(timer);
  }, []);

  useEffect(() => {
    const handleMouseMove = (e) => {
      if (containerRef.current) {
        const rect = containerRef.current.getBoundingClientRect();
        setMousePosition({
          x: ((e.clientX - rect.left) / rect.width) * 100,
          y: ((e.clientY - rect.top) / rect.height) * 100,
        });
      }
    };

    document.addEventListener('mousemove', handleMouseMove);
    return () => document.removeEventListener('mousemove', handleMouseMove);
  }, []);

  const trackClick = (linkName) => {
    console.log('Click tracked:', linkName);
    if (typeof gtag !== 'undefined') {
      gtag('event', 'click', {
        event_category: 'bio_link',
        event_label: linkName,
      });
    }
  };

  const linkData = [
    {
      id: 'auditoria',
      icon: '📊',
      title: 'Auditoria Gratuita',
      description: 'Descubra quanto você está perdendo em 15 minutos',
      url: 'https://wa.me/+5562981109211?text=Olá! Quero fazer uma auditoria gratuita',
      color: 'cyan',
    },
    {
      id: 'consultoria',
      icon: '📞',
      title: 'Agendar Consultoria',
      description: 'Conversa estratégica para acelerar seus resultados',
      url: 'https://wa.me/+5562981109211?text=Olá! Quero agendar uma consultoria',
      color: 'purple',
    },
    {
      id: 'whatsapp',
      icon: '💬',
      title: 'WhatsApp Direto',
      description: 'Vamos conversar sobre seus números?',
      url: 'https://wa.me/+5562981109211?text=Olá! Vim através do link da bio',
      color: 'green',
    },
    {
      id: 'ia_atendente',
      icon: '🤖',
      title: 'Teste nosso atendente IA',
      description: 'Converse com nossa inteligência artificial especializada',
      url: 'https://wa.me/+5562981109211?text=Olá! Quero testar o atendente IA',
      color: 'blue',
    },
  ];

  const FloatingParticles = () => {
    return React.createElement('div', {
      className: 'fixed inset-0 overflow-hidden pointer-events-none',
      style: { zIndex: 0 }
    }, 
      [...Array(25)].map((_, i) => 
        React.createElement('div', {
          key: i,
          className: 'absolute w-1 h-1 rounded-full opacity-30 animate-pulse',
          style: {
            left: `${Math.random() * 100}%`,
            top: `${Math.random() * 100}%`,
            backgroundColor: '#22d3ee',
            animationDelay: `${Math.random() * 5}s`,
            animationDuration: `${3 + Math.random() * 4}s`,
          }
        })
      )
    );
  };

  const LinkCard = ({ link, index }) => {
    const isHovered = hoveredCard === link.id;
    
    return React.createElement('a', {
      href: link.url,
      target: '_blank',
      rel: 'noopener noreferrer',
      className: 'group block p-6 mb-4 glass card-hover shimmer rounded-2xl',
      style: {
        animationDelay: `${index * 0.1}s`,
      },
      onMouseEnter: () => setHoveredCard(link.id),
      onMouseLeave: () => setHoveredCard(null),
      onClick: () => trackClick(link.id)
    }, 
      React.createElement('div', {
        className: 'relative z-10 flex items-center justify-between'
      },
        React.createElement('div', {
          className: 'flex items-center space-x-4'
        },
          React.createElement('div', {
            className: 'text-3xl transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12'
          }, link.icon),
          React.createElement('div', null,
            React.createElement('h3', {
              className: 'text-lg font-bold text-white mb-1 group-hover:text-cyan-300 transition-colors duration-300'
            }, link.title),
            React.createElement('p', {
              className: 'text-sm text-gray-300 group-hover:text-gray-100 transition-colors duration-300'
            }, link.description)
          )
        ),
        React.createElement('div', {
          className: 'text-xl transition-all duration-300 group-hover:translate-x-2 group-hover:text-white group-hover:scale-125',
          style: { color: '#22d3ee' }
        }, '→')
      ),
      isHovered && React.createElement('div', {
        className: 'absolute inset-0 rounded-2xl',
        style: {
          background: `radial-gradient(circle at ${mousePosition.x}% ${mousePosition.y}%, rgba(34,211,238,0.15) 0%, transparent 50%)`,
        }
      })
    );
  };

  const CTAButton = () => React.createElement('a', {
    href: 'https://wa.me/+5562981109211?text=🚀 Olá! Quero transformar meus dados em receita agora!',
    target: '_blank',
    rel: 'noopener noreferrer',
    className: 'btn-primary glow-cyan',
    onClick: () => trackClick('cta_principal')
  }, '🚀 Transformar Meus Dados Agora');

  if (!isLoaded) {
    return React.createElement('div', {
      className: 'min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex items-center justify-center'
    },
      React.createElement('div', {
        className: 'text-center'
      },
        React.createElement('div', {
          className: 'relative'
        },
          React.createElement('div', {
            className: 'w-20 h-20 mx-auto mb-6 bg-white rounded-2xl flex items-center justify-center animate-pulse'
          },
            React.createElement('img', {
              src: 'https://roilabs.com.br/wp-content/uploads/2025/06/Black.png',
              alt: 'ROI Labs Logo',
              className: 'w-12 h-12 object-contain'
            })
          ),
          React.createElement('div', {
            className: 'absolute inset-0 rounded-2xl opacity-30 animate-ping',
            style: {
              background: 'linear-gradient(to right, #22d3ee, #60a5fa)'
            }
          })
        ),
        React.createElement('div', {
          className: 'text-white font-semibold'
        }, 'Carregando...')
      )
    );
  }

  return React.createElement('div', {
    ref: containerRef,
    className: 'min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-hidden'
  },
    React.createElement(FloatingParticles),
    
    // Mouse Follow Gradient
    React.createElement('div', {
      className: 'fixed pointer-events-none opacity-20',
      style: {
        left: `${mousePosition.x}%`,
        top: `${mousePosition.y}%`,
        transform: 'translate(-50%, -50%)',
        width: '400px',
        height: '400px',
        background: 'radial-gradient(circle, rgba(34,211,238,0.3) 0%, transparent 70%)',
        filter: 'blur(100px)',
        transition: 'all 0.3s ease',
        zIndex: 0,
      }
    }),

    React.createElement('div', {
      className: 'container mx-auto px-6 py-12 relative max-w-md',
      style: { zIndex: 10 }
    },
      // Header
      React.createElement('header', {
        className: 'text-center mb-12 animate-fadeIn'
      },
        React.createElement('div', {
          className: 'relative mb-8'
        },
          React.createElement('div', {
            className: 'w-24 h-24 mx-auto bg-white rounded-3xl flex items-center justify-center shadow-2xl shadow-cyan-400/30 hover:scale-105 transition-transform duration-500 group'
          },
            React.createElement('img', {
              src: 'https://roilabs.com.br/wp-content/uploads/2025/06/Black.png',
              alt: 'ROI Labs Logo',
              className: 'w-16 h-16 object-contain'
            }),
            React.createElement('div', {
              className: 'absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500',
              style: {
                background: 'linear-gradient(to right, rgba(34,211,238,0.2), rgba(96,165,250,0.2))'
              }
            })
          ),
          
          // Floating Rings
          React.createElement('div', {
            className: 'absolute inset-0 flex items-center justify-center'
          },
            [...Array(3)].map((_, i) =>
              React.createElement('div', {
                key: i,
                className: 'absolute border rounded-full animate-pulse',
                style: {
                  width: `${120 + i * 40}px`,
                  height: `${120 + i * 40}px`,
                  borderColor: 'rgba(34,211,238,0.2)',
                  animationDelay: `${i * 0.5}s`,
                  animationDuration: '3s',
                }
              })
            )
          )
        ),

        React.createElement('h1', {
          className: 'text-4xl font-bold text-gradient-cyan mb-3'
        }, 'ROI Labs'),
        React.createElement('p', {
          className: 'text-lg text-gray-300 mb-2'
        }, '📊 Analytics • Conversão • Crescimento'),
        React.createElement('p', {
          className: 'text-gray-400'
        }, React.createElement('span', null, '💡 Transformamos dados em receita'), React.createElement('br'), React.createElement('span', null, '🚀 Sua estratégia digital que funciona'))
      ),

      // Links Section
      React.createElement('section', {
        className: 'mb-12'
      },
        React.createElement('h2', {
          className: 'text-xl font-semibold text-center text-white mb-8'
        }, '🎯 Focados em resultados reais'),
        
        React.createElement('div', {
          className: 'space-y-4'
        },
          linkData.map((link, index) =>
            React.createElement(LinkCard, {
              key: link.id,
              link: link,
              index: index
            })
          )
        )
      ),

      // CTA Section
      React.createElement('section', {
        className: 'text-center mb-12'
      },
        React.createElement(CTAButton)
      ),

      // Footer
      React.createElement('footer', {
        className: 'text-center text-gray-500 text-sm'
      },
        React.createElement('p', null, '© 2025 ROI Labs. Transformando dados em receita desde o início.')
      )
    )
  );
};

// Render the component
ReactDOM.render(React.createElement(ROILabsLinkPage), document.getElementById('roi-labs-root'));