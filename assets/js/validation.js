Vue.prototype.$validateText = (v, params, counter = 0) => {
  let validate = [
    v => !!v || `${params} tidak boleh kosong`
  ]

  if (counter > 0) {
    validate.push(v => (v && v.length <= counter) || `Maksimal ${counter} karakter`)
  }

  return validate
}

Vue.prototype.$validateEmail = (v, params) => {
  let validate = [
    v => !!v || `${params} tidak boleh kosong`,
    v => /.+@.+\..+/.test(v) || `${params} belum valid`,
  ]

  return validate
}

Vue.prototype.$validatePhone = (v, params, max = 0, min = 0) => {
  let validate = [
    v => !!v || `${params} tidak boleh kosong`
  ]

  if (max > 0) {
    validate.push(v => (v && v.length <= max) || `Maksimal ${max} karakter`)
  }

  if (min > 0) {
    validate.push(v => (v && v.length >= min) || `Minimal ${min} karakter`)
  }

  return validate
}

Vue.prototype.$validateQuill = (v, params) => {
  return v.length < 1 ? `${params} tidak boleh kosong` : ''
}