import React from "react";
import { Header } from "./Header.jsx";
import { Carousel } from "./Carousel.jsx";
import { Servicios } from "./Servicios.jsx";
import { Habitaciones } from "./Habitaciones.jsx";
import { SobreNosotros } from "./SobreNosotros.jsx";
import { Ubicacion } from "./Ubicacion.jsx";
import { Testimonio } from "./Testimonio.jsx";
import { Footer } from "./Footer.jsx";
export const Home = () => {
  return (
    <>
      <Header />
      <Carousel />
      <Servicios />
      <Habitaciones />
      <SobreNosotros />
      <Ubicacion />
      <Testimonio />
      <Footer />
    </>
  );
};