import React from "react";

export const Ubicacion = () => {
  return (
    <>
      <div id="ubicacion"></div>
      <section className="ubicacion-section">
        <div className="section-header">
          <p className="section-subtitle-2">Conoce</p>
          <h1 className="section-title-2">
            NUESTRA <span>UBICACIÓN</span>
          </h1>
        </div>

        <div className="content-wrapper">
          <div className="info-section">
            <div className="info-item">
              <div className="info-label">Dirección</div>
              <div className="info-value">
                Autopista Medellín-Bogotá #45-132
                <br />
                La Ciudadela, Marinilla
                <br />
                Antioquia, Colombia
              </div>
            </div>

            <div className="info-item">
              <div className="info-label">Ubicación</div>
              <div className="info-value">
                a 45-132,, Autopista Medellín-Bogotá
                <br />
                 #4514, Marinilla, Antioquia
              </div>
            </div>

            <div className="info-item">
              <div className="info-label">Teléfono</div>
              <div className="info-value">
                <a href="tel:+573226483067">+57 3226483067</a>
              </div>
            </div>

            <div className="info-item">
              <div className="info-label">Capacidad</div>
              <div className="info-value">
                52 habitaciones
                <br />
                Capacidad hasta 180 personas
              </div>
            </div>

            <div className="contact-icons">
              <a href="tel:+573226483067" className="icon-btn" title="Llamar">
                <img src="/images/icono-telefono.png" alt="Llamar" />
              </a>
              <a
                href="https://wa.me/573226483067"
                className="icon-btn"
                title="WhatsApp"
                target="_blank"
                rel="noreferrer"
              >
                <img src="/images/icono-mensaje.png" alt="WhatsApp" />
              </a>
              <a
                href="https://maps.app.goo.gl/1DWDVEUopKwg9djZA"
                className="icon-btn"
                title="Cómo llegar"
                target="_blank"
                rel="noreferrer"
              >
                <img src="/images/icono-ubicacion.png" alt="Ubicación" />
              </a>
            </div>
          </div>

          <div className="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.6740468415433!2d-75.35046662524987!3d6.1743750938129995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e469f8eb7c8e655%3A0x5eb4adbb40e9cee8!2sSue%C3%B1o%20Real!5e0!3m2!1ses-419!2sco!4v1778910408302!5m2!1ses-419!2sco" 
            width="600" 
            height="450" 
            style={{ border: 0 }} 
            allowFullScreen
            loading="lazy"
            referrerPolicy="no-referrer-when-downgrade" 
            />
          </div>
        </div>
      </section>
    </>
  );
};