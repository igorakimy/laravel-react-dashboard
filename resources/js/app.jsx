import { StrictMode } from "react";
import { createRoot } from "react-dom/client";

// Imoprt styles file
import "../css/app.css";

const rootElement = document.getElementById("app");
const root = createRoot(rootElement);

root.render(<StrictMode></StrictMode>);
