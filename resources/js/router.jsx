import { createBrowserRouter } from "react-router-dom";
import DefaultLayout from "./components/layouts/DefaultLayout.jsx";
import GuestLayout from "./components/layouts/GuestLayout.jsx";
import Login from "./pages/auth/Login.jsx";
import UsersList from "./pages/users/UsersList.jsx";
import Error404 from "./pages/errors/Error404.jsx";
import Dashboard from "./pages/Dashboard.jsx";
import UserCreate from "./pages/users/UserCreate.jsx";
import RolesList from "./pages/roles/RolesList.jsx";

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
      // {
      //   path: "/users/create",
      //   element: <UserCreate />,
      // },
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
    path: "*",
    element: <Error404 />,
  },
]);

export default router;
