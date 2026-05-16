import React from "react";

export const SobreNosotros = () => {
  return (
    <>
      <div id="sobre-nosotros"></div>
      <section className="porque-elegirnos-section">
        <div className="contenido-razones">
          <h2 className="titulo-seccion">¿Quienes somos?</h2>

          <div className="razones-grid">
            <div className="razon-item">
              <img
                className="icono-razon"
                src="/images/icono-personas.png"
                alt="Quiénes somos"
              />
              <div className="razon-contenido">
                <p>
                  Somos un hotel comprometido con brindar una experiencia
                  inolvidable a nuestros huéspedes. Nuestro objetivo es ofrecer
                  un ambiente acogedor y confortable, donde cada detalle está
                  pensado para su bienestar y satisfacción. Desde nuestras
                  cómodas habitaciones hasta nuestros servicios personalizados,
                  nos esforzamos por superar sus expectativas y hacer que su
                  estancia sea memorable.
                </p>
              </div>
            </div>

            <h2 className="titulo-seccion">¿Por qué elegirnos?</h2>

            <div className="razon-item">
              <img
                className="icono-razon"
                src="/images/icono-estrellas.png"
                alt="Por qué elegirnos"
              />
              <div className="razon-contenido">
                <p>
                  Nos encontramos ubicados en una zona tranquila, es la opcion
                  ideal para quienes buscan comodidad y tranquilidad. Ofrecemos
                  habitaciones modernas y comodas con baño privado, wifi
                  gratuito, espacios elegantes y un servicio amable y
                  perzonalizado. Además, garantizamos estadia confortable y
                  memorable.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div className="imagen-hotel"></div>
      </section>
    </>
  );
};