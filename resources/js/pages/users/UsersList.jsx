import { Box, Button, Card, CardHeader, Typography } from "@mui/material";
import { useEffect, useState } from "react";
import axiosClient from "../../axios-client.js";

export default function UsersList() {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    getUsers();
  }, []);

  const getUsers = () => {
    setLoading(true);
    axiosClient
      .get("/users")
      .then(({ data }) => {
        setLoading(false);
        // setUsers(data);
        console.log(data);
      })
      .catch((err) => {
        setLoading(false);
        console.log(data);
      });
  };

  return (
    <Box>
      <Card>
        <CardHeader
          title="Users"
          action={
            <Button
              variant="contained"
              sx={{ backgroundColor: "secondary.main", color: "white" }}
            >
              Create
            </Button>
          }
        />
      </Card>
    </Box>
  );
}
