terraform {
  required_providers {
    google = {
      source  = "hashicorp/google"
      version = "~> 4.3.0"
    }
  }
}
provider "google" {
  credentials = file("./cred.json")
  project     = "extended-method-356910"
  region      = var.region
  zone        = var.zone

}