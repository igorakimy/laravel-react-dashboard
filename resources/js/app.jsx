import { StrictMode } from "react";
import { createRoot } from "react-dom/client";

const rootElement = document.getElementById("app");
const root = createRoot(rootElement);

function App() {
    return <h1>Hello React!</h1>;
}

root.render(
    <StrictMode>
        <App />
    </StrictMode>
);
