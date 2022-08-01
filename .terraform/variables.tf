variable "region" {
  type = string
}

variable "zone" {
  type = string
}

variable "image" {
  type = string
}

variable "user" {
  type = string
}

variable "machine_type" {
  type    = string
  default = "e2-micro"
}

variable "disk_type" {
  type = string
}
variable "subnetCIDRblock" {
  default = "10.0.0.0/16"
}

variable "CIDRblock" {
  default = "0.0.0.0/0"
}

variable "projectName" {
  type    = string
  default = "default"
}

variable "project" {
  type    = string
}

variable "ssh-key" {
  type    = string
  default = ""
}

variable "startup-script" {
  type    = string
  default = ""
}