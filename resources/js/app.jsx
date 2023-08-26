import { StrictMode } from "react";
import { createRoot } from "react-dom/client";

const rootElement = document.getElementById("app");
const root = createRoot(rootElement);

const apiRoot = "http://laravel-react.local/api";

function App() {
  return <h1>Hello React!</h1>;
}

async function getPizzas() {
  const res = await fetch(`${apiRoot}/pizzas`);
  const data = await res.json();
  return data;
}

function Pizza() {
  const pizzaData = getPizzas();
  return (
    <div>
      <img src="pizzas/pizza1.jpg" alt="" />
      <h2></h2>
      <p></p>
    </div>
  );
}

root.render(
  <StrictMode>
    <App />
    <Pizza />
  </StrictMode>
);
