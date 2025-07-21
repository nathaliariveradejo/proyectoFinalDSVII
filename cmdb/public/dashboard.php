<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TechSolutions - Gestión de Equipos Tecnológicos</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    :root {
      --primary: #1e88e5;
      --primary-dark: #1565c0;
      --secondary: #0d47a1;
      --light: #e3f2fd;
      --dark: #1a237e;
      --gray: #f5f5f5;
      --text: #333;
      --white: #ffffff;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    body {
      background: linear-gradient(135deg, #e3f2fd, #bbdefb);
      color: var(--text);
      min-height: 100vh;
      line-height: 1.6;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    

    header {
      background: linear-gradient(to right, var(--primary), var(--dark));
      color: var(--white);
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 100;
    }
    
    .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
    }
    
    .logo {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .logo-icon {
      font-size: 28px;
      background: var(--white);
      color: var(--primary);
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    
    .logo-text {
      font-size: 24px;
      font-weight: 700;
    }
    
    .logo-text span {
      font-weight: 300;
    }
    
    .user-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--light);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 18px;
    }
    
    .logout-btn {
      background: rgba(255,255,255,0.2);
      border: none;
      padding: 8px 15px;
      border-radius: 30px;
      color: var(--white);
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .logout-btn:hover {
      background: rgba(255,255,255,0.3);
    }
    
    /* Menu Horizontal */
    .menu-horizontal {
      background: var(--white);
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .menu-horizontal ul {
      display: flex;
      list-style: none;
      justify-content: center;
      flex-wrap: wrap;
    }
    
    .menu-horizontal li {
      padding: 0;
    }
    
    .menu-horizontal a {
      display: block;
      padding: 18px 25px;
      color: var(--text);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s;
      position: relative;
    }
    
    .menu-horizontal a:hover, .menu-horizontal a.active {
      color: var(--primary);
      background: rgba(30, 136, 229, 0.05);
    }
    
    .menu-horizontal a.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background: var(--primary);
    }
    

    .dashboard {
      padding: 30px 0;
    }
    
    .welcome-section {
      background: var(--white);
      border-radius: 15px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: var(--shadow);
    }
    
    .welcome-title {
      color: var(--primary);
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .welcome-title i {
      font-size: 28px;
    }
    
    .company-info {
      display: flex;
      gap: 30px;
      margin-top: 25px;
    }
    
    .company-text {
      flex: 1;
    }
    
    .company-stats {
      background: var(--light);
      border-radius: 10px;
      padding: 20px;
      flex: 1;
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
      margin-top: 15px;
    }
    
    .stat-card {
      background: var(--white);
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .stat-value {
      font-size: 28px;
      font-weight: 700;
      color: var(--primary);
      margin: 5px 0;
    }
    
    .stat-label {
      font-size: 14px;
      color: #666;
    }
    

    .section-title {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--light);
    }
    
    .section-title h2 {
      color: var(--dark);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .carousel-container {
      position: relative;
      overflow: hidden;
      border-radius: 15px;
      box-shadow: var(--shadow);
      height: 400px;
      margin-bottom: 40px;
    }
    
    .carousel {
      display: flex;
      height: 100%;
      transition: transform 0.6s ease-in-out;
    }
    
    .carousel-item {
      min-width: 100%;
      position: relative;
      overflow: hidden;
    }
    
    .carousel-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.8);
    }
    
    .carousel-content {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 30px;
      background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
      color: var(--white);
    }
    
    .carousel-tag {
      display: inline-block;
      background: var(--primary);
      color: var(--white);
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 14px;
      margin-bottom: 15px;
    }
    
    .carousel-title {
      font-size: 28px;
      margin-bottom: 10px;
      max-width: 70%;
    }
    
    .carousel-description {
      max-width: 60%;
      opacity: 0.9;
    }
    
    .carousel-nav {
      position: absolute;
      top: 50%;
      width: 100%;
      display: flex;
      justify-content: space-between;
      padding: 0 20px;
      transform: translateY(-50%);
      pointer-events: none;
    }
    
    .carousel-btn {
      pointer-events: all;
      background: rgba(255,255,255,0.8);
      border: none;
      color: var(--dark);
      width: 50px;
      height: 50px;
      border-radius: 50%;
      font-size: 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      transition: all 0.3s;
    }
    
    .carousel-btn:hover {
      background: var(--white);
      transform: scale(1.1);
    }
    
    .carousel-indicators {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 10px;
      z-index: 10;
    }
    
    .indicator {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255,255,255,0.5);
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .indicator.active {
      background: var(--white);
      transform: scale(1.2);
    }
    
 
    .quick-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      margin-bottom: 40px;
    }
    
    .stat-box {
      background: var(--white);
      border-radius: 15px;
      padding: 25px;
      box-shadow: var(--shadow);
      display: flex;
      align-items: center;
      gap: 20px;
      transition: transform 0.3s;
    }
    
    .stat-box:hover {
      transform: translateY(-5px);
    }
    
    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      color: var(--white);
    }
    
    .stat-icon.blue {
      background: linear-gradient(to right, #1e88e5, #0d47a1);
    }
    
    .stat-icon.green {
      background: linear-gradient(to right, #43a047, #2e7d32);
    }
    
    .stat-icon.orange {
      background: linear-gradient(to right, #ff9800, #f57c00);
    }
    
    .stat-icon.purple {
      background: linear-gradient(to right, #8e24aa, #6a1b9a);
    }
    
    .stat-content h3 {
      font-size: 24px;
      margin-bottom: 5px;
    }
    
    .stat-content p {
      color: #666;
      font-size: 14px;
    }
    
 
    footer {
      background: var(--dark);
      color: var(--white);
      padding: 30px 0;
      margin-top: 50px;
    }
    
    .footer-container {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 30px;
    }
    
    .footer-section {
      flex: 1;
      min-width: 250px;
    }
    
    .footer-title {
      font-size: 20px;
      margin-bottom: 20px;
      position: relative;
      padding-bottom: 10px;
    }
    
    .footer-title::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 2px;
      background: var(--primary);
    }
    
    .footer-links {
      list-style: none;
    }
    
    .footer-links li {
      margin-bottom: 12px;
    }
    
    .footer-links a {
      color: #bbdefb;
      text-decoration: none;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .footer-links a:hover {
      color: var(--white);
      padding-left: 5px;
    }
    
    .copyright {
      text-align: center;
      padding-top: 30px;
      margin-top: 30px;
      border-top: 1px solid rgba(255,255,255,0.1);
      color: #90caf9;
      font-size: 14px;
    }
    

    @media (max-width: 900px) {
      .company-info {
        flex-direction: column;
      }
      
      .carousel-title {
        font-size: 24px;
        max-width: 100%;
      }
      
      .carousel-description {
        max-width: 100%;
      }
      
      .menu-horizontal ul {
        flex-wrap: wrap;
      }
    }
    
    @media (max-width: 768px) {
      .header-container {
        flex-direction: column;
        gap: 15px;
      }
      
      .logo {
        margin-bottom: 10px;
      }
      
      .carousel-container {
        height: 350px;
      }
    }
    
    @media (max-width: 576px) {
      .menu-horizontal ul {
        flex-direction: column;
        align-items: center;
      }
      
      .menu-horizontal a {
        padding: 12px 20px;
      }
      
      .carousel-container {
        height: 300px;
      }
      
      .carousel-content {
        padding: 20px;
      }
      
      .carousel-title {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>

  <header>
    <div class="header-container container">
      <div class="logo">
        <div class="logo-icon">
          <i class="fas fa-microchip"></i>
        </div>
        <div class="logo-text">Tech<span>Solutions</span></div>
      </div>
      <div class="user-info">
        <div class="user-avatar"><?= substr($usuario['nombre'], 0, 1) ?></div>
        <div><?= htmlspecialchars($usuario['nombre']) ?></div>
        <button class="logout-btn" onclick="window.location.href='logout.php'">
          <i class="fas fa-sign-out-alt"></i> Salir
        </button>
      </div>
    </div>
  </header>
  
 
  <?php

    include 'menu.php';
  ?>
  

  <div class="dashboard container">

    <section class="welcome-section">
      <h1 class="welcome-title">
        <i class="fas fa-hand-wave"></i> ¡Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?>!
      </h1>
      <p>Esta plataforma te permite gestionar todos los equipos tecnológicos de tu organización, realizar seguimiento de inventario, registrar solicitudes de colaboradores y mantener tu infraestructura actualizada.</p>
      
      <div class="company-info">
        <div class="company-text">
          <h3>Sobre TechSolutions</h3>
          <p>Somos especialistas en gestión de activos tecnológicos, ofreciendo soluciones integrales para empresas que buscan optimizar su infraestructura IT. Con más de 10 años de experiencia, ayudamos a organizaciones a mantener un control preciso de su inventario tecnológico.</p>
          <p>Nuestra plataforma centraliza toda la información de hardware, software y equipos de cómputo, facilitando la administración y el mantenimiento preventivo.</p>
        </div>
        <div class="company-stats">
          <h3>Nuestro Impacto</h3>
          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-value">250+</div>
              <div class="stat-label">Clientes Satisfechos</div>
            </div>
            <div class="stat-card">
              <div class="stat-value">15K+</div>
              <div class="stat-label">Equipos Gestionados</div>
            </div>
            <div class="stat-card">
              <div class="stat-value">98%</div>
              <div class="stat-label">Satisfacción</div>
            </div>
            <div class="stat-card">
              <div class="stat-value">24/7</div>
              <div class="stat-label">Soporte Técnico</div>
            </div>
          </div>
        </div>
      </div>
    </section>
    

    <div class="section-title">
      <h2><i class="fas fa-newspaper"></i> Últimas Noticias Tecnológicas</h2>
      <div class="carousel-indicators" id="indicators">
        <div class="indicator active" data-index="0"></div>
        <div class="indicator" data-index="1"></div>
        <div class="indicator" data-index="2"></div>
        <div class="indicator" data-index="3"></div>
      </div>
    </div>
    
    <div class="carousel-container">
      <div class="carousel" id="carousel">
 
        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1550439062-609e1531270e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&h=600&q=80" alt="Software" class="carousel-image">
          <div class="carousel-content">
            <span class="carousel-tag">Software</span>
            <h2 class="carousel-title">Nuevas Herramientas de Desarrollo para 2023</h2>
            <p class="carousel-description">Descubre las últimas herramientas de desarrollo que están revolucionando la forma en que creamos software, con mejoras en productividad y colaboración.</p>
          </div>
        </div>
        

        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&h=600&q=80" alt="Hardware" class="carousel-image">
          <div class="carousel-content">
            <span class="carousel-tag">Hardware</span>
            <h2 class="carousel-title">Avances en Procesadores para Servidores</h2>
            <p class="carousel-description">Los nuevos procesadores ofrecen un 40% más de rendimiento con un 30% menos de consumo energético, ideal para centros de datos sostenibles.</p>
          </div>
        </div>
        

        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&h=600&q=80" alt="Computadoras" class="carousel-image">
          <div class="carousel-content">
            <span class="carousel-tag">Equipos</span>
            <h2 class="carousel-title">Laptops Corporativas: Comparativa 2023</h2>
            <p class="carousel-description">Análisis de las mejores laptops para entornos corporativos, considerando durabilidad, rendimiento y características de seguridad.</p>
          </div>
        </div>
        

        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1533750349088-cd871a92f312?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&h=600&q=80" alt="Redes" class="carousel-image">
          <div class="carousel-content">
            <span class="carousel-tag">Infraestructura</span>
            <h2 class="carousel-title">Tendencias en Seguridad de Redes</h2>
            <p class="carousel-description">Las nuevas amenazas cibernéticas requieren soluciones avanzadas. Conoce las tendencias en seguridad de redes para proteger tu empresa.</p>
          </div>
        </div>
      </div>
      
      <div class="carousel-nav">
        <button class="carousel-btn" id="prev-btn"><i class="fas fa-chevron-left"></i></button>
        <button class="carousel-btn" id="next-btn"><i class="fas fa-chevron-right"></i></button>
      </div>
    </div>
  </div>
  

  <footer>
    <div class="container footer-container">
      <div class="footer-section">
        <h3 class="footer-title">TechSolutions</h3>
        <p>Especialistas en gestión de activos tecnológicos para empresas que buscan optimizar su infraestructura IT.</p>
      </div>
      
      <div class="footer-section">
        <h3 class="footer-title">Enlaces Rápidos</h3>
        <ul class="footer-links">
          <li><a href="dashboard.php"><i class="fas fa-chevron-right"></i> Inicio</a></li>
          <li><a href="usuarios_listar.php"><i class="fas fa-chevron-right"></i> Usuarios</a></li>
          <li><a href="inventario_listar.php"><i class="fas fa-chevron-right"></i> Inventario</a></li>
          <li><a href="necesidades_listar.php"><i class="fas fa-chevron-right"></i> Solicitudes</a></li>
        </ul>
      </div>
      
      <div class="footer-section">
        <h3 class="footer-title">Contacto</h3>
        <ul class="footer-links">
          <li><a href="#"><i class="fas fa-map-marker-alt"></i> Panamá Norte, PTY</a></li>
          <li><a href="#"><i class="fas fa-phone"></i> (507) 268-0000</a></li>
          <li><a href="#"><i class="fas fa-envelope"></i> info@techsolutions.com</a></li>
          <li><a href="#"><i class="fas fa-clock"></i> Lunes-Viernes: 8am - 5pm</a></li>
        </ul>
      </div>
    </div>
    
    <div class="copyright container">
      &copy; 2025 TechSolutions - Gestión de Equipos Tecnológicos. Todos los derechos reservados.
    </div>
  </footer>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const carousel = document.getElementById('carousel');
      const indicators = document.querySelectorAll('.indicator');
      const prevBtn = document.getElementById('prev-btn');
      const nextBtn = document.getElementById('next-btn');
      let currentIndex = 0;
      const totalSlides = document.querySelectorAll('.carousel-item').length;
      let slideInterval;
      

      function goToSlide(index) {
        currentIndex = (index + totalSlides) % totalSlides;
        carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
        

        indicators.forEach((indicator, i) => {
          if (i === currentIndex) {
            indicator.classList.add('active');
          } else {
            indicator.classList.remove('active');
          }
        });
      }
      

      function nextSlide() {
        goToSlide(currentIndex + 1);
      }
      

      function prevSlide() {
        goToSlide(currentIndex - 1);
      }
      
      function startAutoSlide() {
        slideInterval = setInterval(nextSlide, 5000);
      }
      

      function stopAutoSlide() {
        clearInterval(slideInterval);
      }
      

      prevBtn.addEventListener('click', function() {
        prevSlide();
        stopAutoSlide();
        startAutoSlide();
      });
      
      nextBtn.addEventListener('click', function() {
        nextSlide();
        stopAutoSlide();
        startAutoSlide();
      });
      

      indicators.forEach(indicator => {
        indicator.addEventListener('click', function() {
          const index = parseInt(this.getAttribute('data-index'));
          goToSlide(index);
          stopAutoSlide();
          startAutoSlide();
        });
      });
      

      startAutoSlide();
      

      carousel.addEventListener('mouseenter', stopAutoSlide);
      carousel.addEventListener('mouseleave', startAutoSlide);
    });
  </script>
</body>
</html>