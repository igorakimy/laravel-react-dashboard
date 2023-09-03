import "./bootstrap.js";


// Import styles file
import "../css/main.css";

import { ListItem, ListItemIcon, ListItemText, ThemeProvider, createTheme } from "@mui/material";
import PropTypes from 'prop-types';
import { StrictMode, forwardRef } from "react";
import { createRoot } from "react-dom/client";
import { Link as RouterLink, RouterProvider } from "react-router-dom";
import { ContextProvider } from "./contexts/ContextProvider.jsx";
import router from "./router.jsx";

const root = createRoot(document.getElementById("app"));

const LinkBehavior = forwardRef((props, ref) => {
  const { href, ...other } = props;
  return <RouterLink data-testid="custom-link" ref={ref} to={href} {...other} />;
});

LinkBehavior.propTypes = {
  href: PropTypes.oneOfType([
    PropTypes.shape({
      hash: PropTypes.string,
      pathname: PropTypes.string,
      search: PropTypes.string,
    }),
    PropTypes.string,
  ]).isRequired,
}

const theme = createTheme({
  palette: {
    primary: {
      main: "##fddb3a",
    },
    secondary: {
      main: "#41444b",
    },
    success: {
      main: "#4caf50",
    },
    info: {
      main: "#2196f3",
    },
    light: {
      main: "#f6f4e6",
    },
    dark: {
      main: "#313131",
    },
    danger: {
      main: "#ff4c3f",
    }
  },

  components: {
    MuiLink: {
      defaultProps: {
        component: LinkBehavior,
      },
    },
    MuiButtonBase: {
      defaultProps: {
        LinkComponent: LinkBehavior,
      },
    },
  }
});

// import theme from "./theme";

// function Router(props) {
//   const {children} = props;
//   if (typeof window === 'undefined') {
//     return <StaticRouter location="/">{children}</StaticRouter>
//   }

//   return <MemoryRouter>{children}</MemoryRouter>
// }

root.render(
  <StrictMode>
    <ContextProvider>
      <ThemeProvider theme={theme}>
        <RouterProvider router={router} />
      </ThemeProvider>
    </ContextProvider>
  </StrictMode>
);
