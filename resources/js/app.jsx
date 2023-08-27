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
    <div className={`pizza ${props.sold_out ? "sold-out" : ""}`}>
      <img src={props.photo_name} alt={props.name} />
      <div>
        <h3>{props.name}</h3>
        <p>{props.ingredients}</p>
        <span>{props.sold_out ? "SOLD OUT" : props.price}</span>
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

      {pizzas.length > 0 ? (
        <>
          <p>
            Authentic Italian cuisine. 3 creative dishes to choose from. All
            from our stone oven, all organic, all delicious.
          </p>

          <div className="pizzas">
            {pizzas.map((pizza, i) => {
              return (
                <Pizza
                  key={i}
                  name={pizza.name}
                  photo_name={pizza.photo_name}
                  ingredients={pizza.ingredients}
                  price={pizza.price}
                  sold_out={pizza.sold_out}
                />
              );
            })}
          </div>
        </>
      ) : (
        <p>We're still working on our menu. Please come back later :)</p>
      )}
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
      Current time: <span style={style}>{new Date().toLocaleTimeString()}</span>
      {isOpened ? (
        <Order openHour={openHour} closeHour={closeHour} />
      ) : (
        <p>
          We're happy to welcome you between {openHour}:00 and {closeHour}:00
        </p>
      )}
    </footer>
  );
}

function Order({ openHour, closeHour }) {
  return (
    <div className="order">
      <p>
        We're open from {openHour}:00 to {closeHour}:00. Come visit us or order
        online.
      </p>
      <button className="btn">Order Now</button>
    </div>
  );
}

root.render(
  <StrictMode>
    <App />
  </StrictMode>
);
