import { createContext, useContext, useState } from "react";

const StateContext = createContext({
  user: null,
  token: null,
  setUser: () => {},
  setToken: () => {},
});

export const ContextProvider = ({ children }) => {
  const [user, setUser] = useState();
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

  const can = (permissions) => {
    if (!user || !user?.permissions) return false;

    if (typeof permissions === "string") {
      permissions = permissions.split(/[,;|]/).map((p) => p.trim());
    }
    if (Array.isArray(permissions)) {
      return permissions.some((p) => user.permissions.includes(p));
    }
    return false;
  };

  return (
    <StateContext.Provider
      value={{
        user,
        token,
        setUser,
        setToken,
        can,
      }}
    >
      {children}
    </StateContext.Provider>
  );
};

export const useStateContext = () => useContext(StateContext);
