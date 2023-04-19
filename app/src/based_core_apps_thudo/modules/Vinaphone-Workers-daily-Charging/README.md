# Module charging

Gọi charge cước qua command

```sh
php index.php workers v1 charging [event] [package]
```

trong đó

[event] => Tham số ghi nhận sự kiện: Renew hoặc Retry. Mặc định là Renew

[package] => Tham số ghi nhận gói cước mang đi charge. Mặc định để trống sẽ gọi toàn bộ tập Sub