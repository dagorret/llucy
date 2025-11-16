# Ejemplos de API Moodle vía REST (curl) — Formato multi‑línea

## 1) Crear un usuario en Moodle

```bash
curl -X POST \
  "https://campus.tu-dominio.edu/webservice/rest/server.php" \
  -d "wstoken=TU_TOKEN_MOODLE" \
  -d "wsfunction=core_user_create_users" \
  -d "moodlewsrestformat=json" \
  -d "users[0][username]=juan.perez@alumnos.uti.edu" \
  -d "users[0][email]=juan.perez@alumnos.uti.edu" \
  -d "users[0][firstname]=Juan" \
  -d "users[0][lastname]=Perez" \
  -d "users[0][auth]=oidc"
```

---

## 2) Matricular un usuario en un curso

```bash
curl -X POST \
  "https://campus.tu-dominio.edu/webservice/rest/server.php" \
  -d "wstoken=TU_TOKEN_MOODLE" \
  -d "wsfunction=enrol_manual_enrol_users" \
  -d "moodlewsrestformat=json" \
  -d "users[0][userid]=1234" \
  -d "users[0][courseid]=5678" \
  -d "users[0][roleid]=5"
```

---

## 3) Agregar al usuario a un grupo (comisión)

```bash
curl -X POST \
  "https://campus.tu-dominio.edu/webservice/rest/server.php" \
  -d "wstoken=TU_TOKEN_MOODLE" \
  -d "wsfunction=core_group_add_group_members" \
  -d "moodlewsrestformat=json" \
  -d "members[0][groupid]=42" \
  -d "members[0][userid]=1234"
```

---

## !!! Ejemplo completo: Crear usuario → Matricular → Agregar a grupo

```bash
# 1) Crear usuario
curl -X POST \
  "https://campus.tu-dominio.edu/webservice/rest/server.php" \
  -d "wstoken=TU_TOKEN_MOODLE" \
  -d "wsfunction=core_user_create_users" \
  -d "moodlewsrestformat=json" \
  -d "users[0][username]=juan.perez@alumnos.uti.edu" \
  -d "users[0][email]=juan.perez@alumnos.uti.edu" \
  -d "users[0][firstname]=Juan" \
  -d "users[0][lastname]=Perez" \
  -d "users[0][auth]=oidc"

# 2) Matricular en curso (courseid=5678)
curl -X POST \
  "https://campus.tu-dominio.edu/webservice/rest/server.php" \
  -d "wstoken=TU_TOKEN_MOODLE" \
  -d "wsfunction=enrol_manual_enrol_users" \
  -d "moodlewsrestformat=json" \
  -d "users[0][userid]=1234" \
  -d "users[0][courseid]=5678" \
  -d "users[0][roleid]=5"

# 3) Agregar al grupo (groupid=42)
curl -X POST \
  "https://campus.tu-dominio.edu/webservice/rest/server.php" \
  -d "wstoken=TU_TOKEN_MOODLE" \
  -d "wsfunction=core_group_add_group_members" \
  -d "moodlewsrestformat=json" \
  -d "members[0][groupid]=42" \
  -d "members[0][userid]=1234"
```
