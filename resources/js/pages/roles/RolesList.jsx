import {
  Button,
  Card,
  message,
  Popconfirm,
  Space,
  Table,
  Tag,
  Tooltip,
} from "antd";
import { useStateContext } from "../../contexts/ContextProvider.jsx";
import { useEffect, useState } from "react";
import { DeleteFilled, EditFilled } from "@ant-design/icons";
import axiosClient from "../../axios-client.js";
import RoleCreateForm from "../../components/forms/RoleCreateForm.jsx";
import RoleUpdateForm from "../../components/forms/RoleUpdateForm.jsx";
import { useNavigate } from "react-router-dom";

const RolesList = () => {
  const navigate = useNavigate();
  const { can } = useStateContext();
  const [openCreateForm, setOpenCreateForm] = useState(false);
  const [openUpdateForm, setOpenUpdateForm] = useState(false);
  const [roles, setRoles] = useState([]);
  const [role, setRole] = useState({});
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

  const getColumns = () => [
    {
      title: "ID",
      dataIndex: "id",
      sorter: true,
    },
    {
      title: "Name",
      dataIndex: "name",
      sorter: true,
    },
    {
      title: "Permissions",
      dataIndex: "permissions",
      sorter: true,
      render: (_, { permissions }) => (
        <Space size={[0, 4]} wrap>
          {permissions.length > 0 ? (
            permissions.slice(0, 2).map((permission) => {
              return (
                <Tag color="blue" key={permission.name}>
                  {permission.display_name}
                </Tag>
              );
            })
          ) : (
            <Tag color="blue" key="all">
              All
            </Tag>
          )}
          {permissions.length > 2 ? '...' : ''}
        </Space>
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
      width: 80,
      render: (_, render) => (
        <Space size="small">
          {can("roles.update") && render.name !== "Super Admin" && (
            <Tooltip placement="top" title="Edit">
              <Button
                size="small"
                onClick={() => showUpdateRoleForm(render.id)}
                icon={<EditFilled style={{ color: "#456cec" }} />}
              />
            </Tooltip>
          )}
          {can("roles.destroy") && render.name !== "Super Admin" && (
            <Popconfirm
              placement="topLeft"
              title="Are you sure to delete this role"
              onConfirm={() => handleRoleDelete(render.id)}
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

  useEffect(() => {
    if (!can("roles.index")) {
      navigate("/forbidden");
    }

    getRoles();
  }, [JSON.stringify(tableParams)]);

  const getRoles = () => {
    setLoading(true);
    axiosClient
      .get("/roles", {
        params: getTableParams(tableParams),
      })
      .then(({ data }) => {
        setRoles(data.data);
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
      setRoles([]);
    }
  };

  const getTableParams = (params) => ({
    ...params,
  });

  // Handle user creating.
  const handleRoleCreate = (values) => {
    showLoadingMessage("Creating...");

    axiosClient
      .post("/roles", values)
      .then(({ data }) => {
        setOpenCreateForm(false);
        getRoles();
        showMessage("success", "Role successfully created!");
      })
      .catch(({ response }) => {
        const err = response?.data?.errors;
        if (err) {
          setErrors(err);
        }
        const msg = response?.data?.message;
        if (msg && !err) {
          showMessage("error", "Failed to create role: " + msg);
        }
      });
  };

  const showUpdateRoleForm = (roleId) => {
    axiosClient
      .get("/roles/" + roleId)
      .then(({ data }) => {
        setRole(data);
        setOpenUpdateForm(true);
      })
      .catch(({ response }) => {
        showMessage("error", response?.data?.message);
      });
  };

  // Handle user updating.
  const handleRoleUpdate = (roleId, values) => {
    showLoadingMessage("Updating...");

    axiosClient
      .put("/roles/" + roleId, values)
      .then(({ data }) => {
        setOpenUpdateForm(false);
        getRoles();
        showMessage("success", "Role successfully updated!");
      })
      .catch(({ response }) => {
        const err = response?.data?.errors;
        if (err) {
          setErrors(err);
        }
        const msg = response?.data?.message;
        if (msg && !err) {
          showMessage("error", "Failed to update role: " + msg);
        }
      });
  };

  // Handle user deleting.
  const handleRoleDelete = (roleId) => {
    showLoadingMessage("Deleting...");

    axiosClient
      .delete("/roles/" + roleId)
      .then(({ data }) => {
        getRoles();
        showMessage("success", "Role successfully deleted!");
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
      title="Roles"
      extra={
        can("roles.store") ? (
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
        }}
        columns={getColumns()}
        rowKey={(record) => record.id}
        dataSource={roles}
        pagination={tableParams.pagination}
        loading={loading}
        onChange={handleTableChange}
        scroll={{ y: 450, x: 500 }}
        size="small"
      />

      <RoleUpdateForm
        open={openUpdateForm}
        role={role}
        onUpdate={handleRoleUpdate}
        onCancel={() => setOpenUpdateForm(false)}
        errors={errors}
        onError={handleErrors}
      />

      <RoleCreateForm
        open={openCreateForm}
        onCreate={handleRoleCreate}
        onCancel={() => setOpenCreateForm(false)}
        errors={errors}
        onError={handleErrors}
      />
    </Card>
  );
};

export default RolesList;
