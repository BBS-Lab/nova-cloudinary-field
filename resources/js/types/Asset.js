export default class Asset {
  constructor(bytes, created_at, duration, format, height, metadata, public_id, resource_type, secure_url, tags, type, url, version, width) {
    this.bytes = bytes
    this.created_at = created_at
    this.duration = duration
    this.format = format
    this.height = height
    this.metadata = metadata
    this.public_id = public_id
    this.resource_type = resource_type
    this.secure_url = secure_url
    this.tags = tags
    this.type = type
    this.url = url
    this.version = version
    this.width = width
  }
}
