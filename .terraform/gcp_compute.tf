resource "google_compute_instance" "php_instance" {
  name         = "${var.projectName}-php"
  machine_type = var.machine_type
  boot_disk {
    source = google_compute_disk.this.name
  }
  network_interface {
    subnetwork = google_compute_subnetwork.test_network_subnetwork.name
    access_config {

    }
  }
  metadata = {
    ssh-keys = "${file("~/Desktop/gantry_google.pub")}"
  }
}
#-----------------------------------------------------------

resource "google_compute_disk" "this" {
  project = "extended-method-356910"
  name    = "${var.projectName}-php"
  type    = var.disk_type
  zone    = var.zone
  size    = 250
  image = var.image
}