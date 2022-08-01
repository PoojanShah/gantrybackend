output "webip" {
  value = google_compute_instance.php_instance.network_interface.0.access_config.0.nat_ip
}
