terraform {
  backend "gcs" {
    credentials = "./cred.json"
    bucket      = "gantrybackend-terraform"
  }
}

