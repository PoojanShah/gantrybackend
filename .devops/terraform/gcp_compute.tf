resource "google_compute_instance" "php_instance" {
  name         = "${var.projectName}-php"
  machine_type = var.machine_type
  boot_disk {
    source = google_compute_disk.this.name
  }
  network_interface {
    subnetwork = google_compute_subnetwork.test_network_subnetwork.name
    access_config {
        nat_ip = google_compute_address.this.address
    }
  }
  tags = ["http-server","https-server"]
  metadata = {
    ssh-keys = "${var.user}:${file("~/Desktop/gantry.pub")}"
  }
}
#-----------------------------------------------------------

resource "google_compute_disk" "this" {
  project = var.project
  name    = "${var.projectName}-php"
  type    = var.disk_type
  zone    = var.zone
  size    = 250
  image   = var.image
}
#-----------------------------------------------------------

resource "google_compute_address" "this" {
  name = "${var.projectName}-php"
}
