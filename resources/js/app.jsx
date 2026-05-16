import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import { AppRoutes } from './habitaciones/AppRoutes';
import './css/index.css';

ReactDOM.createRoot(document.getElementById('app')).render(
    <BrowserRouter>
        <AppRoutes />
    </BrowserRouter>
);