import React from "react";
import { Link, useLocation } from "react-router-dom";
import { Menu } from "antd";
import {
  TeamOutlined,
  ShoppingOutlined,
  DashboardOutlined,
  SettingOutlined,
} from "@ant-design/icons";
import { useStateContext } from "../contexts/ContextProvider.jsx";

const SidebarMenu = () => {
  const { can } = useStateContext();
  const location = useLocation();

  function getItem(label, permission, key, path, icon, items) {
    return {
      key,
      permission,
      path,
      icon,
      items,
      label,
    };
  }

  const items = [
    getItem("Dashboard", null, "1", "/", <DashboardOutlined />),
    getItem("Access", null, "2", "accessSubmenu", <TeamOutlined />, [
      getItem("Users", "users.index", "3", "/users"),
      getItem("Roles", "roles.index", "4", "/roles"),
      getItem("Invitations", "invitations.index", "5", "/invitations"),
    ]),
    getItem("Inventory", null, "6", "inventorySubmenu", <ShoppingOutlined />, [
      getItem("Products", "products.index", "7", "/products"),
      getItem("Categories", "categories.index", "8", "/categories"),
    ]),
    getItem(
      "Settings",
      "settings.index",
      "9",
      "/settings",
      <SettingOutlined />,
    ),
  ];

  return (
    <Menu
      theme="dark"
      mode="inline"
      selectedKeys={[location.pathname]}
      defaultSelectedKeys={[location.pathname]}
      defaultOpenKeys={[
        location.pathname,
        "accessSubmenu",
        "inventorySubmenu",
        "settingsSubmenu",
      ]}
    >
      {items.map((item) =>
        item.items ? (
          item.permission === null || can(item.permission) ? (
            <Menu.SubMenu key={item.path} icon={item.icon} title={item.label}>
              {item.items.map((child) =>
                child.permission === null || can(child.permission) ? (
                  <Menu.Item style={{ userSelect: "none" }} key={child.path}>
                    <Link to={child.path}>{child.label}</Link>
                  </Menu.Item>
                ) : null,
              )}
            </Menu.SubMenu>
          ) : null
        ) : (
          (item.permission === null || can(item.permission)) && (
            <Menu.Item icon={item.icon} key={item.path}>
              <Link to={item.path}>{item.label}</Link>
            </Menu.Item>
          )
        ),
      )}
    </Menu>
  );
};

export default SidebarMenu;
