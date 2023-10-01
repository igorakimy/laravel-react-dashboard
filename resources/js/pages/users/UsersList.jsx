import { DeleteFilled, EditFilled } from "@ant-design/icons";
import {
  Button,
  Card,
  Popconfirm,
  Space,
  Table,
  Tooltip,
  message,
  Tag,
} from "antd";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";
import UserCreateForm from "../../components/forms/UserCreateForm.jsx";
import UserUpdateForm from "../../components/forms/UserUpdateForm.jsx";
import { useStateContext } from "../../contexts/ContextProvider.jsx";

export default function UsersList() {
  const { can, currentUser } = useStateContext();
  const [openCreateForm, setOpenCreateForm] = useState(false);
  const [openUpdateForm, setOpenUpdateForm] = useState(false);
  const [users, setUsers] = useState([]);
  const [user, setUser] = useState({});
  const [loading, setLoading] = useState(false);
  const [messageApi, contextHolder] = message.useMessage();
  const [errors, setErrors] = useState({});
  const [tableParams, setTableParams] = useState({
    pagination: {
      current: 1,
      pageSize: 10,
      pageSizeOptions: ["10", "25", "50", "100", "500", "1000"],
      showQuickJumper: true,
    },
  });

  useEffect(() => {
    getUsers();
  }, [JSON.stringify(tableParams)]);

  const getColumns = () => [
    {
      title: "ID",
      dataIndex: "id",
      sorter: true,
      width: 80,
    },
    {
      title: "Name",
      dataIndex: "name",
      sorter: true,
      render: (_, render) => {
        return render.full_name;
      },
    },
    {
      title: "Email",
      dataIndex: "email",
      sorter: true,
    },
    {
      title: "Roles",
      dataIndex: "roles",
      sorter: true,
      width: 243,
      render: (_, { roles }) => (
        <Space size={[0, 8]} wrap>
          {roles.map((role) => {
            return (
              <Tag color="blue" key={role.name}>
                {role.name.toLowerCase()}
              </Tag>
            );
          })}
        </Space>
      ),
    },
    {
      title: "Status",
      dataIndex: "status",
      sorter: true,
      render: (_, render) => (
        <Tag color={render.status === "active" ? "green" : "red"}>
          {render.status}
        </Tag>
      ),
    },
    {
      title: "Created At",
      dataIndex: "created_at",
      sorter: true,
    },
    {
      title: "Updated At",
      dataIndex: "updated_at",
      sorter: true,
    },
    {
      title: "Actions",
      align: "center",
      width: 90,
      render: (_, render) => (
        <Space size="small">
          {can("users.update") && (
            <Tooltip placement="top" title="Edit">
              <Button
                size="small"
                onClick={() => showUpdateUserForm(render.id)}
                icon={<EditFilled style={{ color: "#456cec" }} />}
              />
            </Tooltip>
          )}
          {can("users.destroy") && currentUser.id !== render.id && (
            <Popconfirm
              placement="topLeft"
              title="Are you sure to delete this user"
              onConfirm={() => handleUserDelete(render.id)}
              okText="Yes"
              cancelText="No"
            >
              <Tooltip placement="top" title="Delete">
                <Button
                  size="small"
                  icon={<DeleteFilled style={{ color: "#ec4545" }} />}
                />
              </Tooltip>
            </Popconfirm>
          )}
        </Space>
      ),
    },
  ];

  const getUsers = () => {
    setLoading(true);
    axiosClient
      .get("/users", {
        params: getTableParams(tableParams),
      })
      .then(({ data }) => {
        setUsers(data.data);
        setLoading(false);
        setTableParams({
          ...tableParams,
          pagination: {
            ...tableParams.pagination,
            current: data.meta.current_page,
            pageSize: data.meta.per_page,
            total: data.meta.total,
            showTotal: (total, range) =>
              `${range[0]}-${range[1]} of ${total} items`,
          },
        });
      })
      .catch((err) => {
        setLoading(false);
      });
  };

  const handleTableChange = (pagination, filters, sorter) => {
    setTableParams({
      pagination,
      filters,
      ...sorter,
    });

    if (pagination.pageSize !== tableParams.pagination?.pageSize) {
      setUsers([]);
    }
  };

  const getTableParams = (params) => ({
    ...params,
  });

  // Handle user creating.
  const handleUserCreate = (values) => {
    showLoadingMessage("Creating...");

    axiosClient
      .post("/users", values)
      .then(({ data }) => {
        setOpenCreateForm(false);
        getUsers();
        showMessage("success", "User successfully created!");
      })
      .catch(({ response }) => {
        const err = response?.data?.errors;
        if (err) {
          setErrors(err);
        }
        const msg = response?.data?.message;
        if (msg && !err) {
          showMessage("error", "Failed to сreate user: " + msg);
        }
      });
  };

  const showUpdateUserForm = (userId) => {
    axiosClient
      .get("/users/" + userId)
      .then(({ data }) => {
        setUser(data);
        setOpenUpdateForm(true);
      })
      .catch(({ response }) => {
        showMessage("error", response?.data?.message);
      });
  };

  // Handle user updating.
  const handleUserUpdate = (userId, values) => {
    showLoadingMessage("Updating...");

    axiosClient
      .put("/users/" + userId, values)
      .then(({ data }) => {
        setOpenUpdateForm(false);
        getUsers();
        showMessage("success", "User successfully updated!");
      })
      .catch(({ response }) => {
        const err = response?.data?.errors;
        if (err) {
          setErrors(err);
        }
        const msg = response?.data?.message;
        if (msg && !err) {
          showMessage("error", "Failed to update user: " + msg);
        }
      });
  };

  // Handle user deleting.
  const handleUserDelete = (userId) => {
    showLoadingMessage("Deleting...");

    axiosClient
      .delete("/users/" + userId)
      .then(({ data }) => {
        getUsers();
        showMessage("success", "User successfully deleted!");
      })
      .catch(({ response }) => {
        showMessage("error", "Failed to delete user: ");
      });
  };

  const handleErrors = () => {
    setErrors(errors);
  };

  const showLoadingMessage = (content) => {
    messageApi.open({
      key: "updatable",
      type: "loading",
      content: content,
    });
  };

  const showMessage = (type, content) => {
    messageApi.open({
      key: "updatable",
      type: type,
      content: content,
    });
  };

  return (
    <Card
      type="inner"
      title="Users"
      extra={
        can("users.store") ? (
          <Button
            size="small"
            type="primary"
            onClick={() => {
              setOpenCreateForm(true);
            }}
          >
            Create
          </Button>
        ) : null
      }
    >
      {contextHolder}

      <Table
        rowSelection={{
          type: "checkbox",
          columnWidth: 32,
        }}
        columns={getColumns()}
        rowKey={(record) => record.id}
        dataSource={users}
        pagination={tableParams.pagination}
        loading={loading}
        onChange={handleTableChange}
        // virtual
        scroll={{ y: 450, x: "max-content" }}
        size="small"
      />

      <UserCreateForm
        open={openCreateForm}
        onCreate={handleUserCreate}
        onCancel={() => setOpenCreateForm(false)}
        errors={errors}
        onError={handleErrors}
      />

      <UserUpdateForm
        open={openUpdateForm}
        onUpdate={handleUserUpdate}
        onCancel={() => setOpenUpdateForm(false)}
        errors={errors}
        onError={handleErrors}
        user={user}
      />
    </Card>
  );
}
