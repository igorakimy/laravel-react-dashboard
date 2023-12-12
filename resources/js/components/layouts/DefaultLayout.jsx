import { MenuFoldOutlined, MenuUnfoldOutlined } from "@ant-design/icons";
import { Button, Layout, theme } from "antd";
import { useEffect, useState } from "react";
import { Link, Navigate, Outlet } from "react-router-dom";
import axiosClient from "../../axios-client";
import { useStateContext } from "../../contexts/ContextProvider";

import SidebarMenu from "../SidebarMenu.jsx";
import UserDropdown from "../UserDropdown.jsx";
import LogoIcon from "../icons/LogoIcon.jsx";

export default function DefaultLayout() {
  const { currentUser, token, setCurrentUser, setToken } = useStateContext();
  const [open, setOpen] = useState(true);
  const [anchorEl, setAnchorEl] = useState(null);
  const [collapsed, setCollapsed] = useState(false);

  const { Header, Sider, Content, Footer } = Layout;

  useEffect(() => {
    getCurrentUser();
  }, []);

  const {
    token: { colorBgContainer },
  } = theme.useToken();

  const handleDrawerOpen = () => {
    setOpen(true);
  };

  const handleDrawerClose = () => {
    setOpen(false);
  };

  const handleMenu = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  const handleLogout = (event) => {
    event.preventDefault();

    axiosClient.post("/logout").then((resp) => {
      setCurrentUser(null);
      setToken(null);
    });
  };

  const getCurrentUser = () => {
    axiosClient.get("/user").then(({ data }) => {
      setCurrentUser(data);
    });
  };

  if (!token) {
    return <Navigate to="/login" />;
  }

  return (
    <Layout hasSider>
      <Sider
        collapsed={collapsed}
        collapsedWidth="0"
        style={{
          overflow: "auto",
          height: "100vh",
          position: "fixed",
          zIndex: 100,
          width: "250px",
          left: 0,
          top: 0,
          bottom: 0,
        }}
      >
        <div
          style={{
            textAlign: "center",
            margin: "0.6rem 0",
          }}
        >
          <Link to="/">
            <LogoIcon style={{ color: "#ffeb00" }} />
          </Link>
        </div>

        <SidebarMenu />
      </Sider>
      <Layout
        style={{
          marginLeft: collapsed ? 0 : 200,
        }}
      >
        <Header
          style={{
            padding: 0,
            position: "sticky",
            top: 0,
            zIndex: 99,
            background: colorBgContainer,
          }}
        >
          <Button
            type="text"
            icon={collapsed ? <MenuUnfoldOutlined /> : <MenuFoldOutlined />}
            onClick={() => setCollapsed(!collapsed)}
            style={{
              fontSize: "16px",
              width: 64,
              height: 64,
            }}
          ></Button>

          <UserDropdown logout={handleLogout} />
        </Header>
        <Content
          style={{
            margin: "24px 16px",
            padding: 0,
            minHeight: "calc(100vh - 112px)",
            background: "#f5f5f5",
          }}
        >
          <Outlet />
        </Content>
      </Layout>
    </Layout>
  );
}
