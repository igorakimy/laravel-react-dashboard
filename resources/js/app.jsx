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

function Pizza(props) {
  const pizzaData = getPizzas();
  return (
    <div className="pizza">
      <img src={props.photo_name} alt={props.name} />
      <div>
        <h3>{props.name}</h3>
        <p>{props.ingredients}</p>
        <span>{props.price + 3}</span>
      </div>
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
      <div className="pizzas">
        <Pizza
          name="Pizza Name"
          photo_name="pizzas/pizza1.jpg"
          ingredients="Ingredients"
          price={20}
        />
      </div>
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
