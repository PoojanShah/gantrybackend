resource "google_dns_managed_zone" "backend" {
  name     = var.dns_zone_name
  dns_name = "comfort-health.net."
}


resource "google_dns_record_set" "api" {
  name = "api.${google_dns_managed_zone.backend.dns_name}"
  type = "A"
  ttl  = 300

  managed_zone = google_dns_managed_zone.backend.name

  rrdatas = [google_compute_instance.php_instance.network_interface[0].access_config[0].nat_ip]
}

resource "google_dns_record_set" "api_cname" {
  name         = "www.api.${google_dns_managed_zone.backend.dns_name}"
  managed_zone = google_dns_managed_zone.backend.name
  type         = "CNAME"
  ttl          = 300
  rrdatas      = ["api.${google_dns_managed_zone.backend.dns_name}"]
}
