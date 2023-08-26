import { StrictMode, useEffect, useState } from "react";
import { createRoot } from "react-dom/client";

// Imoprt styles file
import "../css/app.css";

const rootElement = document.getElementById("app");
const root = createRoot(rootElement);

const apiRoot = "http://laravel-react.local/api";

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
  return (
    <div className="pizza">
      <img src={props.photo_name} alt={props.name} />
      <div>
        <h3>{props.name}</h3>
        <p>{props.ingredients}</p>
        <span>{props.price}</span>
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
  const [pizzas, setPizzas] = useState([]);

  useEffect(() => {
    fetch(`${apiRoot}/pizzas`)
      .then((res) => res.json())
      .then((data) => setPizzas(data.data));
  }, []);

  return (
    <div className="menu">
      <h2>Our Menu</h2>
      <div className="pizzas">
        {pizzas.map((pizza, i) => {
          return (
            <Pizza
              key={i}
              name={pizza.name}
              photo_name={pizza.photo_name}
              ingredients={pizza.ingredients}
              price={pizza.price}
            />
          );
        })}
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
