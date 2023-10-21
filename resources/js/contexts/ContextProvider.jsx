import { createContext, useContext, useState } from "react";

const StateContext = createContext({
  currentUser: null,
  token: null,
  setCurrentUser: () => {},
  setToken: () => {},
});

export const ContextProvider = ({ children }) => {
  const [currentUser, _setCurrentUser] = useState(
    JSON.parse(localStorage.getItem("CURRENT_USER")),
  );
  const [token, _setToken] = useState(localStorage.getItem("ACCESS_TOKEN"));

  const setToken = (token) => {
    _setToken(token);
    // если токен был передан...
    if (token) {
      // ... сохраняем токен в локальном хранилище
      localStorage.setItem("ACCESS_TOKEN", token);
    } else {
      localStorage.removeItem("ACCESS_TOKEN");
    }
  };

  const setCurrentUser = (user) => {
    _setCurrentUser(user);

    if (user) {
      user = JSON.stringify(user);
      localStorage.setItem("CURRENT_USER", user);
    } else {
      localStorage.removeItem("CURRENT_USER");
    }
  };

  const can = (permissions) => {
    // console.log(currentUser);
    if (
      !currentUser ||
      (!currentUser?.permissions && !currentUser.isSuperAdmin)
    )
      return false;

    if (typeof permissions === "string") {
      permissions = permissions.split(/[,;|]/).map((p) => p.trim());
    }
    if (Array.isArray(permissions)) {
      return (
        currentUser.isSuperAdmin ||
        permissions.some((p) => currentUser.permissions.includes(p))
      );
    }
    return false;
  };

  return (
    <StateContext.Provider
      value={{
        currentUser,
        setCurrentUser,
        token,
        setToken,
        can,
      }}
    >
      {children}
    </StateContext.Provider>
  );
};

export const useStateContext = () => useContext(StateContext);
