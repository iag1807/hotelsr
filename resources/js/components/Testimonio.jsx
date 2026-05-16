import React from "react";

export const Testimonio = () => {
  return (
    <>
    <div id="reseñas"></div>
    <section className="testimonio-section">
      <div className="lado-reserva">
        <div className="fondo-reserva">
          <div className="reserva-contenido">
            <p className="reserva-subtitulo">
              Hacemos de tu descanso una experiencia única, combinando
              tranquilidad y un servicio que convierte cada momento en un
            </p>
            <h2 className="reserva-titulo">
              <span>Sueño Real</span>
            </h2>
            <a href="/register" className="btn-reserva">
              Reservar
            </a>
          </div>
        </div>
      </div>

      <div className="lado-testimonio">
        <div className="testimonio-contenido">
          <p className="testimonio-texto">
            La estadia en el Hotel Sueño Real fue simplemente maravillosa. La
            habitacion era super comoda y con un ambiente moderno que me hizo
            sentir como en casa. El personal fue amable en todo momento y
            siempre dispuesto a ayudar. La tranquilidad del lugar y la buena
            ubicacion en Marinilla hicieron que mi viaje fuera perfecto.
            Definitivamente volveria a hospedarme aqui.
          </p>
          <div className="testimonio-autor">
            <div className="autor-avatar">
              <img src="/images/icono-usuario.png" alt="Usuario" />
            </div>
            <div className="autor-info">
              <h4>Cristina Zapata Morales</h4>
              <p>Huesped</p>
            </div>
          </div>
        </div>
      </div>
    </section></>
  );
};