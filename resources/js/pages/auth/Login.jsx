import {
  Alert,
  Box,
  Button,
  Checkbox,
  Container,
  FormControlLabel,
  Grid,
  IconButton,
  Link,
  TextField,
  Typography,
} from "@mui/material";
import CloseIcon from "@mui/icons-material/Close";
import axiosClient from "../../axios-client";
import { useStateContext } from "../../contexts/ContextProvider";
import { useState } from "react";

export default function Login() {
  const { setUser, setToken } = useStateContext();
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState("");

  const handleSubmit = (event) => {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    // console.log(Object.fromEntries(data.entries()));

    axiosClient
      .post("/login", Object.fromEntries(data.entries()))
      .then(({ data }) => {
        setUser(data.user);
        setToken(data.token);
      })
      .catch((err) => {
        setErrors({});

        const resp = err.response;
        if (resp && resp.status === 422) {
          setErrors(resp.data.errors);
        }
        setMessage(resp.data.message);
        console.log(resp.data.message);
      });
  };

  return (
    <Container component="main" maxWidth="xs">
      <Box
        sx={{
          marginTop: 8,
          display: "flex",
          flexDirection: "column",
          alignItems: "center",
        }}
      >
        <Typography component="h1" variant="h5">
          Sign In
        </Typography>

        <Box component="form" onSubmit={handleSubmit} noValidate sx={{ mt: 1 }}>
          {message ? (
            <Alert
              icon={false}
              severity="error"
              action={
                <IconButton
                  aria-label="close"
                  color="inherit"
                  size="small"
                  onClick={() => {
                    setMessage("");
                  }}
                >
                  <CloseIcon fontSize="inherit" />
                </IconButton>
              }
              sx={{
                mt: 1,
                maxWidth: "100%",
              }}
            >
              {typeof message === "string" ? message : message.join("\n")}
            </Alert>
          ) : null}

          <TextField
            margin="normal"
            required
            fullWidth
            id="email"
            label="Email Address"
            name="email"
            autoComplete="email"
            autoFocus
            error={errors && errors.email}
            helperText={errors && errors.email ? errors.email[0] : null}
          />
          <TextField
            margin="normal"
            required
            fullWidth
            id="password"
            label="Password"
            name="password"
            type="password"
            autoComplete="current-password"
            error={errors && errors.password}
            helperText={errors && errors.password ? errors.password[0] : null}
          />

          <Grid container>
            <Grid item xs>
              <FormControlLabel
                control={<Checkbox value="remember" color="primary" />}
                label="Remember me"
                variant="body2"
                sx={{ userSelect: "none" }}
              />
            </Grid>
            <Grid item xs sx={{ textAlign: "right", pt: 1 }}>
              <Link href="/users" variant="body1">
                Forgot password?
              </Link>
            </Grid>
          </Grid>

          <Button
            type="submit"
            fullWidth
            variant="contained"
            sx={{ mt: 3, mb: 2 }}
          >
            Sign In
          </Button>
        </Box>
      </Box>
    </Container>
  );
}
