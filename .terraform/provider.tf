provider "google" {
  credentials = file("./cred.json")
  project     = "extended-method-356910"
  region      = var.region
  zone        = var.zone

}