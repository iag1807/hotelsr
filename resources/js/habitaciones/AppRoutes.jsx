import React, { Suspense, lazy } from "react";
import { Routes, Route } from "react-router-dom";

import { Home } from "../components/Home";
const Sencilla = lazy(() => import("./Sencilla").then((module) => ({ default: module.Sencilla })));
const Bañera = lazy(() => import("./Bañera").then((module) => ({ default: module.Bañera })));
const Jacuzzi = lazy(() => import("./Jacuzzi").then((module) => ({ default: module.Jacuzzi })));
const Doble = lazy(() => import("./Doble").then((module) => ({ default: module.Doble })));
const Triple = lazy(() => import("./Triple").then((module) => ({ default: module.Triple })));
const Multiple = lazy(() => import("./Multiple").then((module) => ({ default: module.Multiple })));

const Loading = () => <div>Cargando...</div>;

export const AppRoutes = () => {
  return (
    <Suspense fallback={<Loading />}>
      <Routes>
        {/* Ruta principal que muestra la página Home completa */}
        <Route path="/" element={<Home />} />

        {/* Rutas de cada habitación */}
        <Route path="/habitaciones/sencilla" element={<Sencilla />} />
        <Route path="/habitaciones/bañera" element={<Bañera />} />
        <Route path="/habitaciones/jacuzzi" element={<Jacuzzi />} />
        <Route path="/habitaciones/doble" element={<Doble />} />
        <Route path="/habitaciones/triple" element={<Triple />} />
        <Route path="/habitaciones/multiple" element={<Multiple />} />
      </Routes>
    </Suspense>
  );
};