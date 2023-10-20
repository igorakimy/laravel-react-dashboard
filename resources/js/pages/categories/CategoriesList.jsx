import {
  Button,
  Card,
  message,
  Popconfirm,
  Segmented,
  Space,
  Table,
  Tooltip,
  Tree,
} from "antd";
import { useStateContext } from "../../contexts/ContextProvider.jsx";
import { useEffect, useState } from "react";
import Icon, {
  BarsOutlined,
  DeleteFilled,
  EditFilled,
  TableOutlined,
} from "@ant-design/icons";
import axiosClient from "../../axios-client.js";
import CategoryUpdateForm from "../../components/forms/CategoryUpdateForm.jsx";
import CategoryCreateForm from "../../components/forms/CategoryCreateForm.jsx";

const CategoriesList = () => {
  const { can } = useStateContext();
  const [openCreateForm, setOpenCreateForm] = useState(false);
  const [openUpdateForm, setOpenUpdateForm] = useState(false);
  const [openTable, setOpenTable] = useState(false);
  const [openList, setOpenList] = useState(false);
  const [viewType, setViewType] = useState("Table");
  const [treeData, setTreeData] = useState([]);
  const [expandedKeys, setExpandedKeys] = useState([]);
  const [categories, setCategories] = useState([]);
  const [category, setCategory] = useState({});
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

  const { TreeNode } = Tree;

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
          {can("categories.update") && (
            <Tooltip placement="top" title="Edit">
              <Button
                size="small"
                onClick={() => showUpdateCategoryForm(render.id)}
                icon={<EditFilled style={{ color: "#456cec" }} />}
              />
            </Tooltip>
          )}
          {can("categories.destroy") && (
            <Popconfirm
              placement="topLeft"
              title="Are you sure to delete this category"
              onConfirm={() => handleCategoryDelete(render.id)}
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
    setOpenTable(true);
    setOpenList(false);
    getCategories();
    getCategoriesForTree();
  }, [JSON.stringify(tableParams)]);

  const getCategories = () => {
    setLoading(true);
    axiosClient
      .get("/categories", {
        params: getTableParams(tableParams),
      })
      .then(({ data }) => {
        setCategories(data.data);
        // console.log(convertToTreeData(data.data));
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
        console.log(err);
        setLoading(false);
      });
  };

  const getCategoriesForTree = () => {
    axiosClient
      .get("/categories?kind=tree")
      .then(({ data }) => {
        setTreeData(
          convertToTreeData(data.filter((item) => item.parent === null)),
        );
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const convertToTreeData = (data) => {
    return data.map((item) => {
      return {
        key: item.id,
        parent: item.parent,
        title: item.name,
        value: item.name,
        children:
          item.children && item.children.length > 0
            ? convertToTreeData(item.children)
            : null,
      };
    });
  };

  const handleTableChange = (pagination, filters, sorter) => {
    setTableParams({
      pagination,
      filters,
      ...sorter,
    });

    if (pagination.pageSize !== tableParams.pagination?.pageSize) {
      setCategories([]);
    }
  };

  const getTableParams = (params) => ({
    ...params,
  });

  // Handle user creating.
  const handleCategoryCreate = (values) => {
    showLoadingMessage("Creating...");

    axiosClient
      .post("/categories", values)
      .then(({ data }) => {
        setOpenCreateForm(false);
        getCategories();
        getCategoriesForTree();
        showMessage("success", "Category successfully created!");
      })
      .catch(({ response }) => {
        const err = response?.data?.errors;
        if (err) {
          setErrors(err);
        }
        const msg = response?.data?.message;
        if (msg && !err) {
          showMessage("error", "Failed to create category: " + msg);
        }
      });
  };

  const showUpdateCategoryForm = (id) => {
    axiosClient
      .get("/categories/" + id)
      .then(({ data }) => {
        setCategory(data);
        setOpenUpdateForm(true);
      })
      .catch(({ response }) => {
        showMessage("error", response?.data?.message);
      });
  };

  // Handle user updating.
  const handleCategoryUpdate = (id, values) => {
    showLoadingMessage("Updating...");

    axiosClient
      .put("/categories/" + id, values)
      .then(({ data }) => {
        setOpenUpdateForm(false);
        getCategories();
        getCategoriesForTree();
        showMessage("success", "Category successfully updated!");
      })
      .catch(({ response }) => {
        const err = response?.data?.errors;
        if (err) {
          setErrors(err);
        }
        const msg = response?.data?.message;
        if (msg && !err) {
          showMessage("error", "Failed to update category: " + msg);
        }
      });
  };

  // Handle user deleting.
  const handleCategoryDelete = (id) => {
    showLoadingMessage("Deleting...");

    axiosClient
      .delete("/categories/" + id)
      .then(({ data }) => {
        getCategories();
        getCategoriesForTree();
        showMessage("success", "Category successfully deleted!");
      })
      .catch(({ response }) => {
        showMessage("error", "Failed to delete category: ");
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

  const handleChangeCategoriesView = (value) => {
    if (value === "Table") {
      setViewType("Table");
    } else {
      setViewType("Tree");
    }
  };

  const renderTreeNodes = (data) =>
    data.map((item) => {
      item.title = (
        <Space style={{ padding: 4 }}>
          {item.value}
          <Space size={8} style={{ marginLeft: 8 }}>
            <Button
              size="small"
              onClick={() => showUpdateCategoryForm(item.key)}
              icon={<EditFilled style={{ color: "#456cec" }} />}
            />
            <Popconfirm
              placement="topLeft"
              title="Are you sure to delete this category"
              onConfirm={() => handleCategoryDelete(item.key)}
              okText="Yes"
              cancelText="No"
            >
              <Button
                size="small"
                icon={<DeleteFilled style={{ color: "#ec4545" }} />}
              />
            </Popconfirm>
          </Space>
        </Space>
      );

      if (item.children) {
        return (
          <TreeNode
            selectable={false}
            title={item.title}
            key={item.key}
            dataRef={item}
          >
            {renderTreeNodes(item.children)}
          </TreeNode>
        );
      }

      return <TreeNode selectable={false} {...item} />;
    });

  return (
    <Card
      type="inner"
      title="Categories"
      extra={
        can("categories.store") ? (
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

      <Segmented
        style={{
          marginBottom: 8,
        }}
        defaultValue="Table"
        onChange={handleChangeCategoriesView}
        options={[
          {
            label: "Table",
            value: "Table",
            icon: <TableOutlined />,
          },
          {
            label: "Tree",
            value: "Tree",
            icon: <BarsOutlined />,
          },
        ]}
      />

      <Table
        childrenColumnName="childs"
        rowSelection={{
          type: "checkbox",
        }}
        columns={getColumns()}
        rowKey={(record) => record.id}
        dataSource={categories}
        pagination={tableParams.pagination}
        loading={loading}
        onChange={handleTableChange}
        scroll={{ y: 450, x: 500 }}
        size="small"
        style={{
          display: viewType === "Table" ? "block" : "none",
        }}
      />

      <Tree
        showLine={true}
        style={{
          display: viewType === "Tree" ? "block" : "none",
        }}
        showIcon={false}
      >
        {renderTreeNodes(treeData)}
      </Tree>

      <CategoryUpdateForm
        open={openUpdateForm}
        category={category}
        onUpdate={handleCategoryUpdate}
        onCancel={() => setOpenUpdateForm(false)}
        errors={errors}
        onError={handleErrors}
      />

      <CategoryCreateForm
        open={openCreateForm}
        onCreate={handleCategoryCreate}
        onCancel={() => setOpenCreateForm(false)}
        errors={errors}
        onError={handleErrors}
      />
    </Card>
  );
};

export default CategoriesList;
