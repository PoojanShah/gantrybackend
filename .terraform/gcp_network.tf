resource "google_compute_network" "this" {
  name = "${var.projectName}-network"
}
#-----------------------------------------------------------
resource "google_compute_firewall" "default" {
  name    = "${var.projectName}-firewall"
  network = google_compute_network.this.name
  allow {
    protocol = "icmp"
  }
  dynamic "allow" {
    for_each = ["80", "443", "22"]
    content {
      protocol = "tcp"
      ports    = [allow.value]
    }
  }
  source_tags   = ["php-backend"]
  source_ranges = [var.CIDRblock]
}
#-----------------------------------------------------------
resource "google_compute_subnetwork" "test_network_subnetwork" {
  name          = "${var.projectName}-subnetwork"
  region        = var.region
  network       = google_compute_network.this.self_link
  ip_cidr_range = var.subnetCIDRblock
}

