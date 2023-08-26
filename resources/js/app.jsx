import { StrictMode } from "react";
import { createRoot } from "react-dom/client";

// Imoprt styles file
import "../css/app.css";

const rootElement = document.getElementById("app");
const root = createRoot(rootElement);

const apiRoot = "http://laravel-react.local/api";

async function getPizzas() {
  const res = await fetch(`${apiRoot}/pizzas`);
  const data = await res.json();
  return data;
}

function App() {
  return (
    <div className="container">
      <Header />
      <Menu />
      <Footer />
    </div>
  );
}

function Pizza() {
  const pizzaData = getPizzas();
  return (
    <div className="pizza">
      <img src="pizzas/pizza1.jpg" alt="" />
      <h3></h3>
      <p></p>
    </div>
  );
}

function Header() {
  return (
    <header className="header">
      <h1>Fast React Pizza Co.</h1>
    </header>
  );
}

function Menu() {
  return (
    <div className="menu">
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

  const style = {
    color: "#f00",
  };

  return (
    <footer>
      <span style={style}>{new Date().toLocaleTimeString()}</span>. We're
      currently open!
    </footer>
  );
}

root.render(
  <StrictMode>
    <App />
  </StrictMode>
);
