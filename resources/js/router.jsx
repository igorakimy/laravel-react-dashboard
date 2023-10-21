import { createBrowserRouter } from "react-router-dom";
import DefaultLayout from "./components/layouts/DefaultLayout.jsx";
import GuestLayout from "./components/layouts/GuestLayout.jsx";
import Login from "./pages/auth/Login.jsx";
import UsersList from "./pages/users/UsersList.jsx";
import Error404 from "./pages/errors/Error404.jsx";
import Dashboard from "./pages/Dashboard.jsx";
import RolesList from "./pages/roles/RolesList.jsx";
import ProductsList from "./pages/products/ProductsList.jsx";
import CategoriesList from "./pages/categories/CategoriesList.jsx";
import Settings from "./pages/settings/Settings.jsx";
import Error403 from "./pages/errors/Error403.jsx";

const router = createBrowserRouter([
  {
    path: "/",
    element: <DefaultLayout />,
    children: [
      {
        path: "/",
        element: <Dashboard />,
      },
      {
        path: "/users",
        element: <UsersList />,
      },
      {
        path: "/roles",
        element: <RolesList />,
      },
      {
        path: "/products",
        element: <ProductsList />,
      },
      {
        path: "/categories",
        element: <CategoriesList />,
      },
      {
        path: "/settings",
        element: <Settings />,
      },
    ],
  },

  {
    path: "/",
    element: <GuestLayout />,
    children: [
      {
        path: "/login",
        element: <Login />,
      },
    ],
  },

  {
    path: "/forbidden",
    element: <Error403 />,
  },
  {
    path: "*",
    element: <Error404 />,
  },
]);

export default router;
