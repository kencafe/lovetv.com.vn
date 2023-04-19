# Charge History

Lưu trữ lịch sử nâng cấp của project

### Change History

| Revision | Date       | Author          | Remark                   |
| -------- | ---------- | --------------- | ------------------------ |
| 1.0.1    | 2018-06-24 | tungnt@gviet.vn | Update sync CDR Vascloud |

1. Thêm libraries: Libs_db_cdr_logs

```
/based_core_apps_thudo/libraries/Vina_Services/Libs_db_cdr_logs.php
```

2. Thêm module: Db_cdr_log_model:

```
/based_core_apps_thudo/models/Vina_Services/Db_cdr_log_model.php
```

3. Update lại thuật toán đồng bộ file CDR theo phương thức mới: 

```
/based_core_apps_thudo/modules/Vinaphone-Webservices-Vascloud-CDR/controllers/Worker_cdr.php
```



| Revision | Date       | Author          | Remark          |
| -------- | ---------- | --------------- | --------------- |
| 1.0      | 2018-04-11 | hungna@gviet.vn | Update Document |

