import { StrictMode } from "react";
import { createRoot } from "react-dom/client";

const rootElement = document.getElementById("app");
const root = createRoot(rootElement);

const apiRoot = "http://laravel-react.local/api";

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

function Header() {
  return <h1>Fast React Pizza Co.</h1>;
}

function Menu() {
  return (
    <div>
      <h2>Our Menu</h2>
      <Pizza />
    </div>
  );
}

function Footer() {
  const hour = new Date().getHours();
  const openHour = 12;
  const closeHour = 22;
  const isOpened = hour >= openHour && hour <= closeHour;

  return (
    <footer>{new Date().toLocaleTimeString()}. We're currently open!</footer>
  );
}

root.render(
  <StrictMode>
    <Header />
    <Menu />
    <Footer />
  </StrictMode>
);
