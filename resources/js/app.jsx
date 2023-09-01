import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import Main from "./Main";

// Import styles file
import "../css/app.css";

const root = createRoot(document.getElementById("app"));

root.render(
  <StrictMode>
    <Main />
  </StrictMode>
);
