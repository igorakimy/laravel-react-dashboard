import "./bootstrap.js";

// Import styles file
import "../css/main.css";

import { StrictMode, forwardRef } from "react";
import { createRoot } from "react-dom/client";
import { RouterProvider } from "react-router-dom";
import { ContextProvider } from "./contexts/ContextProvider.jsx";
import router from "./router.jsx";

const root = createRoot(document.getElementById("app"));

root.render(
  <StrictMode>
    <ContextProvider>
      <RouterProvider router={router} />
    </ContextProvider>
  </StrictMode>,
);
