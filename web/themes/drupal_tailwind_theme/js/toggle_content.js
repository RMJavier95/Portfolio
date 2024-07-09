document.addEventListener("DOMContentLoaded", function() {
  let readMoreButtons = document.querySelectorAll('.read-more');
  
  readMoreButtons.forEach(function(button) {
    button.addEventListener('click', function() {
      let parent = this.closest('.field__item');
      let preview = parent.querySelector('.content-preview');
      let full = parent.querySelector('.content-full');
      
      if (full.classList.contains('hidden')) {
        preview.classList.add('hidden');
        full.classList.remove('hidden');
        full.classList.add('animate-fade-down', 'animate-once', 'animate-ease-out');  // Añadir animación
        this.textContent = 'Mostrar menos';
      } else {
        full.classList.add('hidden');
        full.classList.remove('animate-fade-down', 'animate-once', 'animate-ease-out');  // Quitar animación
        preview.classList.remove('hidden');
        this.textContent = 'Leer más';
      }
    });
  });
});


document.addEventListener("DOMContentLoaded", function() {
  const links = document.querySelectorAll("nav ul li a");
  links.forEach(link => {
      link.addEventListener("click", function(event) {
          event.preventDefault();
          const targetContent = this.textContent;
          const targetElement = document.querySelector(`[data-content="${targetContent}"]`);
          if (targetElement) {
              window.scrollTo({
                  top: targetElement.offsetTop - document.querySelector('nav').offsetHeight,
                  behavior: "smooth"
              });
          }
      });
  });
});


  document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.getElementById('navbar');
    const links = document.querySelectorAll("nav ul li a");

    window.addEventListener('scroll', function() {
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    links.forEach(link => {
      link.addEventListener("click", function(event) {
        event.preventDefault();
        const targetClass = this.getAttribute("data-target");
        const targetElement = document.querySelector(targetClass);
        if (targetElement) {
          window.scrollTo({
            top: targetElement.offsetTop - navbar.offsetHeight,
            behavior: "smooth"
          });
        }
      });
    });
  });


// JavaScript
document.addEventListener("DOMContentLoaded", function() {
  const images = document.querySelectorAll(".js-animate-on-scroll");

  function checkVisibility() {
    images.forEach(image => {
      const imageTop = image.getBoundingClientRect().top;
      const imageBottom = image.getBoundingClientRect().bottom;
      const windowHeight = window.innerHeight;

      // Calcular el punto medio de la ventana visible
      const windowMidPoint = windowHeight / 2;

      // Calcular el punto medio vertical de la imagen
      const imageMidPoint = (imageTop + imageBottom) / 2;

      // Añadir la clase animate-fade-down solo cuando la imagen está a la mitad de la ventana
      if (imageMidPoint < windowMidPoint && imageBottom > windowMidPoint) {
        image.classList.add("animate-fade-down");
        image.classList.remove("opacity-0");
      }
    });
  }

  window.addEventListener("scroll", checkVisibility);
  window.addEventListener("resize", checkVisibility);

  // Llamar a la función inicialmente para comprobar la visibilidad al cargar la página
  checkVisibility();
});



  