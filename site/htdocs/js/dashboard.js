document.addEventListener("DOMContentLoaded", () => {
  // Dashboard Navigation
  const navLinks = document.querySelectorAll(".sidebar-nav a")
  const dashboardSections = document.querySelectorAll(".dashboard-section")

  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault()

      // Get the target section
      const targetId = this.getAttribute("href").slice(1)
      const targetSection = document.getElementById(targetId)

      // Hide all sections
      dashboardSections.forEach((section) => {
        section.classList.remove("active")
      })

      // Show target section
      if (targetSection) {
        targetSection.classList.add("active")

        // Update active nav link
        navLinks.forEach((link) => {
          link.parentElement.classList.remove("active")
        })
        this.parentElement.classList.add("active")
      }

      // Close mobile menu if open
      if (window.innerWidth < 992) {
        document.querySelector(".dashboard-sidebar").classList.remove("active")
      }
    })
  })

  // Dropdown Toggle
  const dropdownToggle = document.querySelector(".dropdown-toggle")
  const dropdown = document.querySelector(".dropdown")

  if (dropdownToggle && dropdown) {
    dropdownToggle.addEventListener("click", () => {
      dropdown.classList.toggle("active")
    })

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!dropdown.contains(e.target)) {
        dropdown.classList.remove("active")
      }
    })
  }

  // Collapsible Sections
  const collapsibleHeaders = document.querySelectorAll(".collapsible-header")

  collapsibleHeaders.forEach((header) => {
    header.addEventListener("click", function () {
      const section = this.parentElement
      section.classList.toggle("active")

      // Toggle hidden class on content
      const content = section.querySelector(".collapsible-content")
      if (content) {
        content.classList.toggle("hidden", !section.classList.contains("active"))
      }
    })
  })

  // File Upload Preview
  const fileInputs = document.querySelectorAll(".file-input")

  fileInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const file = this.files[0]
      if (file) {
        const preview = this.closest(".image-item").querySelector("img")

        const reader = new FileReader()
        reader.onload = (e) => {
          preview.src = e.target.result
        }
        reader.readAsDataURL(file)
      }
    })
  })

  // Workshop Toggle
  const workshopToggle = document.getElementById("workshopActive")

  if (workshopToggle) {
    workshopToggle.addEventListener("change", function () {
      // Save the state to localStorage
      const isActive = this.checked
      localStorage.setItem("workshopActive", isActive ? "true" : "false")
      console.log(`Workshop is now ${isActive ? "active" : "inactive"}`)
      showSuccessMessage(`Workshop ${isActive ? "ativado" : "desativado"} com sucesso!`)
    })

    // Load saved state
    const savedState = localStorage.getItem("workshopActive")
    if (savedState !== null) {
      workshopToggle.checked = savedState === "true"
    }
  }

  // Copy URL to Clipboard
  const copyButtons = document.querySelectorAll(".copy-url")

  copyButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const url = this.getAttribute("data-url")

      navigator.clipboard.writeText(url).then(() => {
        // Show temporary feedback
        const originalText = this.innerHTML
        this.innerHTML = '<i class="fas fa-check"></i>'

        setTimeout(() => {
          this.innerHTML = originalText
        }, 2000)
      })
    })
  })

  // Drag and Drop File Upload
  const dropZone = document.querySelector(".drop-zone")

  if (dropZone) {
    const dropZoneInput = dropZone.querySelector(".drop-zone-input")

    dropZone.addEventListener("click", () => {
      dropZoneInput.click()
    })

    dropZoneInput.addEventListener("change", function () {
      if (this.files.length) {
        updateDropZoneFiles(this.files)
      }
    })

    dropZone.addEventListener("dragover", (e) => {
      e.preventDefault()
      dropZone.classList.add("drop-zone-over")
    })
    ;["dragleave", "dragend"].forEach((type) => {
      dropZone.addEventListener(type, () => {
        dropZone.classList.remove("drop-zone-over")
      })
    })

    dropZone.addEventListener("drop", (e) => {
      e.preventDefault()

      if (e.dataTransfer.files.length) {
        dropZoneInput.files = e.dataTransfer.files
        updateDropZoneFiles(e.dataTransfer.files)
      }

      dropZone.classList.remove("drop-zone-over")
    })

    function updateDropZoneFiles(files) {
      if (files.length > 0) {
        const prompt = dropZone.querySelector(".drop-zone-prompt")
        prompt.textContent = `${files.length} arquivo(s) selecionado(s)`
      }
    }
  }

  // Delete Media Confirmation
  const deleteButtons = document.querySelectorAll(".delete-media")

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      if (confirm("Tem certeza que deseja excluir este arquivo?")) {
        // Here you would typically send a request to the server to delete the file
        const mediaItem = this.closest(".media-item")
        mediaItem.remove()
        showSuccessMessage("Arquivo excluído com sucesso!")
      }
    })
  })

  // Form Submissions
  const contentForm = document.getElementById("contentForm")
  const workshopForm = document.getElementById("workshopForm")
  const uploadForm = document.getElementById("uploadForm")
  const faqForm = document.getElementById("faqForm")
  const teamForm = document.getElementById("teamForm")
  const servicesForm = document.getElementById("servicesForm")

  if (contentForm) {
    contentForm.addEventListener("submit", (e) => {
      e.preventDefault()
      // Here you would typically send the form data to the server
      showSuccessMessage("Conteúdo atualizado com sucesso!")
    })
  }

  if (workshopForm) {
    workshopForm.addEventListener("submit", (e) => {
      e.preventDefault()
      // Here you would typically send the form data to the server
      showSuccessMessage("Informações do workshop atualizadas com sucesso!")
    })
  }

  if (uploadForm) {
    uploadForm.addEventListener("submit", (e) => {
      e.preventDefault()
      const fileInput = document.getElementById("fileUpload")
      if (fileInput.files.length === 0) {
        alert("Por favor, selecione pelo menos um arquivo")
        return
      }
      // Here you would typically send the files to the server
      showSuccessMessage("Arquivos enviados com sucesso!")
    })
  }

  if (faqForm) {
    faqForm.addEventListener("submit", (e) => {
      e.preventDefault()
      showSuccessMessage("Perguntas frequentes atualizadas com sucesso!")
    })
  }

  if (teamForm) {
    teamForm.addEventListener("submit", (e) => {
      e.preventDefault()
      showSuccessMessage("Informações da equipe atualizadas com sucesso!")
    })
  }

  if (servicesForm) {
    servicesForm.addEventListener("submit", (e) => {
      e.preventDefault()
      showSuccessMessage("Serviços atualizados com sucesso!")
    })
  }

  // Add FAQ Item
  const addFaqButton = document.getElementById("addFaqButton")
  if (addFaqButton) {
    addFaqButton.addEventListener("click", () => {
      const faqContainer = document.querySelector(".faq-items-container")
      const faqCount = faqContainer.querySelectorAll(".collapsible-section").length + 1

      const newFaqItem = document.createElement("div")
      newFaqItem.className = "collapsible-section"
      newFaqItem.innerHTML = `
        <div class="collapsible-header">
          <h4>Pergunta ${faqCount}</h4>
          <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="collapsible-content">
          <div class="form-group">
            <label>Pergunta</label>
            <input type="text" name="faqQuestion${faqCount}" value="">
          </div>
          <div class="form-group">
            <label>Resposta</label>
            <textarea name="faqAnswer${faqCount}" rows="3"></textarea>
          </div>
          <button type="button" class="secondary-button remove-item">Remover</button>
        </div>
      `

      faqContainer.appendChild(newFaqItem)

      // Add event listener to the new collapsible header
      const newHeader = newFaqItem.querySelector(".collapsible-header")
      newHeader.addEventListener("click", () => {
        newFaqItem.classList.toggle("active")
        const content = newFaqItem.querySelector(".collapsible-content")
        content.classList.toggle("hidden", !newFaqItem.classList.contains("active"))
      })

      // Add event listener to the remove button
      const removeButton = newFaqItem.querySelector(".remove-item")
      removeButton.addEventListener("click", (e) => {
        e.stopPropagation()
        if (confirm("Tem certeza que deseja remover esta pergunta?")) {
          newFaqItem.remove()
          showSuccessMessage("Pergunta removida com sucesso!")
        }
      })

      // Open the new item
      newFaqItem.classList.add("active")
    })
  }

  // Add Team Member
  const addTeamButton = document.getElementById("addTeamButton")
  if (addTeamButton) {
    addTeamButton.addEventListener("click", () => {
      const teamContainer = document.querySelector(".team-items-container")
      const teamCount = teamContainer.querySelectorAll(".collapsible-section").length + 1

      const newTeamItem = document.createElement("div")
      newTeamItem.className = "collapsible-section"
      newTeamItem.innerHTML = `
        <div class="collapsible-header">
          <h4>Membro ${teamCount}</h4>
          <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="collapsible-content">
          <div class="form-group">
            <label>Nome</label>
            <input type="text" name="teamName${teamCount}" value="">
          </div>
          <div class="form-group">
            <label>Especialidade</label>
            <input type="text" name="teamSpecialty${teamCount}" value="">
          </div>
          <div class="form-group">
            <label>Currículo</label>
            <textarea name="teamBio${teamCount}" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label>Foto</label>
            <div class="image-preview" style="height: 150px; margin-bottom: 10px;">
              <img src="/placeholder.svg" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <input type="file" id="teamPhoto${teamCount}" name="teamPhoto${teamCount}" class="file-input" accept="image/*">
            <label for="teamPhoto${teamCount}" class="upload-button">Escolher Imagem</label>
          </div>
          <button type="button" class="secondary-button remove-item">Remover</button>
        </div>
      `

      teamContainer.appendChild(newTeamItem)

      // Add event listener to the new collapsible header
      const newHeader = newTeamItem.querySelector(".collapsible-header")
      newHeader.addEventListener("click", () => {
        newTeamItem.classList.toggle("active")
        const content = newTeamItem.querySelector(".collapsible-content")
        content.classList.toggle("hidden", !newTeamItem.classList.contains("active"))
      })

      // Add event listener to the file input
      const fileInput = newTeamItem.querySelector(".file-input")
      fileInput.addEventListener("change", function () {
        const file = this.files[0]
        if (file) {
          const preview = this.closest(".form-group").querySelector("img")
          const reader = new FileReader()
          reader.onload = (e) => {
            preview.src = e.target.result
          }
          reader.readAsDataURL(file)
        }
      })

      // Add event listener to the remove button
      const removeButton = newTeamItem.querySelector(".remove-item")
      removeButton.addEventListener("click", (e) => {
        e.stopPropagation()
        if (confirm("Tem certeza que deseja remover este membro?")) {
          newTeamItem.remove()
          showSuccessMessage("Membro removido com sucesso!")
        }
      })

      // Open the new item
      newTeamItem.classList.add("active")
    })
  }

  // Add Service
  const addServiceButton = document.getElementById("addServiceButton")
  if (addServiceButton) {
    addServiceButton.addEventListener("click", () => {
      const serviceContainer = document.querySelector(".service-items-container")
      const serviceCount = serviceContainer.querySelectorAll(".collapsible-section").length + 1

      const newServiceItem = document.createElement("div")
      newServiceItem.className = "collapsible-section"
      newServiceItem.innerHTML = `
        <div class="collapsible-header">
          <h4>Serviço ${serviceCount}</h4>
          <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="collapsible-content">
          <div class="form-group">
            <label>Título</label>
            <input type="text" name="serviceTitle${serviceCount}" value="">
          </div>
          <div class="form-group">
            <label>Descrição</label>
            <textarea name="serviceDesc${serviceCount}" rows="3"></textarea>
          </div>
          <button type="button" class="secondary-button remove-item">Remover</button>
        </div>
      `

      serviceContainer.appendChild(newServiceItem)

      // Add event listener to the new collapsible header
      const newHeader = newServiceItem.querySelector(".collapsible-header")
      newHeader.addEventListener("click", () => {
        newServiceItem.classList.toggle("active")
        const content = newServiceItem.querySelector(".collapsible-content")
        content.classList.toggle("hidden", !newServiceItem.classList.contains("active"))
      })

      // Add event listener to the remove button
      const removeButton = newServiceItem.querySelector(".remove-item")
      removeButton.addEventListener("click", (e) => {
        e.stopPropagation()
        if (confirm("Tem certeza que deseja remover este serviço?")) {
          newServiceItem.remove()
          showSuccessMessage("Serviço removido com sucesso!")
        }
      })

      // Open the new item
      newServiceItem.classList.add("active")
    })
  }

  // Initialize remove buttons for existing items
  const removeButtons = document.querySelectorAll(".remove-item")
  removeButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.stopPropagation()
      const item = this.closest(".collapsible-section")
      const itemType = item.getAttribute("data-type") || "item"

      if (confirm(`Tem certeza que deseja remover este ${itemType}?`)) {
        item.remove()
        showSuccessMessage(`${itemType.charAt(0).toUpperCase() + itemType.slice(1)} removido com sucesso!`)
      }
    })
  })

  function showSuccessMessage(message) {
    // Create a floating message
    const messageElement = document.createElement("div")
    messageElement.className = "success-message"
    messageElement.textContent = message

    // Style the message
    messageElement.style.position = "fixed"
    messageElement.style.bottom = "20px"
    messageElement.style.right = "20px"
    messageElement.style.backgroundColor = "var(--success)"
    messageElement.style.color = "white"
    messageElement.style.padding = "15px 20px"
    messageElement.style.borderRadius = "5px"
    messageElement.style.boxShadow = "0 3px 10px rgba(0, 0, 0, 0.2)"
    messageElement.style.zIndex = "1000"
    messageElement.style.opacity = "0"
    messageElement.style.transform = "translateY(20px)"
    messageElement.style.transition = "opacity 0.3s ease, transform 0.3s ease"

    // Add to document
    document.body.appendChild(messageElement)

    // Animate in
    setTimeout(() => {
      messageElement.style.opacity = "1"
      messageElement.style.transform = "translateY(0)"
    }, 10)

    // Remove after delay
    setTimeout(() => {
      messageElement.style.opacity = "0"
      messageElement.style.transform = "translateY(20px)"

      setTimeout(() => {
        document.body.removeChild(messageElement)
      }, 300)
    }, 3000)
  }
})

document.addEventListener("DOMContentLoaded", () => {
  // Dashboard Navigation
  const navLinks = document.querySelectorAll(".sidebar-nav a")
  const dashboardSections = document.querySelectorAll(".dashboard-section")

  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault()

      // Get the target section
      const targetId = this.getAttribute("href").slice(1)
      const targetSection = document.getElementById(targetId)

      // Hide all sections
      dashboardSections.forEach((section) => {
        section.classList.remove("active")
      })

      // Show target section
      if (targetSection) {
        targetSection.classList.add("active")

        // Update active nav link
        navLinks.forEach((link) => {
          link.parentElement.classList.remove("active")
        })
        this.parentElement.classList.add("active")
      }

      // Close mobile menu if open
      if (window.innerWidth < 992) {
        document.querySelector(".dashboard-sidebar").classList.remove("active")
      }
    })
  })

  // Dropdown Toggle
  const dropdownToggle = document.querySelector(".dropdown-toggle")
  const dropdown = document.querySelector(".dropdown")

  if (dropdownToggle && dropdown) {
    dropdownToggle.addEventListener("click", () => {
      dropdown.classList.toggle("active")
    })

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
      if (!dropdown.contains(e.target)) {
        dropdown.classList.remove("active")
      }
    })
  }

  // Collapsible Sections
  const collapsibleHeaders = document.querySelectorAll(".collapsible-header")

  collapsibleHeaders.forEach((header) => {
    header.addEventListener("click", function () {
      const section = this.parentElement
      section.classList.toggle("active")

      // Toggle hidden class on content
      const content = section.querySelector(".collapsible-content")
      if (content) {
        content.classList.toggle("hidden", !section.classList.contains("active"))
      }
    })
  })

  // File Upload Preview
  const fileInputs = document.querySelectorAll(".file-input")

  fileInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const file = this.files[0]
      if (file) {
        const preview = this.closest(".image-item").querySelector("img")

        const reader = new FileReader()
        reader.onload = (e) => {
          preview.src = e.target.result
        }
        reader.readAsDataURL(file)
      }
    })
  })

  // Workshop Toggle
  const workshopToggle = document.getElementById("workshopActive")

  if (workshopToggle) {
    workshopToggle.addEventListener("change", function () {
      // Save the state to localStorage
      const isActive = this.checked
      localStorage.setItem("workshopActive", isActive ? "true" : "false")
      console.log(`Workshop is now ${isActive ? "active" : "inactive"}`)
      showSuccessMessage(`Workshop ${isActive ? "ativado" : "desativado"} com sucesso!`)
    })

    // Load saved state
    const savedState = localStorage.getItem("workshopActive")
    if (savedState !== null) {
      workshopToggle.checked = savedState === "true"
    }
  }

  // Copy URL to Clipboard
  const copyButtons = document.querySelectorAll(".copy-url")

  copyButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const url = this.getAttribute("data-url")

      navigator.clipboard.writeText(url).then(() => {
        // Show temporary feedback
        const originalText = this.innerHTML
        this.innerHTML = '<i class="fas fa-check"></i>'

        setTimeout(() => {
          this.innerHTML = originalText
        }, 2000)
      })
    })
  })

  // Drag and Drop File Upload
  const dropZone = document.querySelector(".drop-zone")

  if (dropZone) {
    const dropZoneInput = dropZone.querySelector(".drop-zone-input")

    dropZone.addEventListener("click", () => {
      dropZoneInput.click()
    })

    dropZoneInput.addEventListener("change", function () {
      if (this.files.length) {
        updateDropZoneFiles(this.files)
      }
    })

    dropZone.addEventListener("dragover", (e) => {
      e.preventDefault()
      dropZone.classList.add("drop-zone-over")
    })
    ;["dragleave", "dragend"].forEach((type) => {
      dropZone.addEventListener(type, () => {
        dropZone.classList.remove("drop-zone-over")
      })
    })

    dropZone.addEventListener("drop", (e) => {
      e.preventDefault()

      if (e.dataTransfer.files.length) {
        dropZoneInput.files = e.dataTransfer.files
        updateDropZoneFiles(e.dataTransfer.files)
      }

      dropZone.classList.remove("drop-zone-over")
    })

    function updateDropZoneFiles(files) {
      if (files.length > 0) {
        const prompt = dropZone.querySelector(".drop-zone-prompt")
        prompt.textContent = `${files.length} arquivo(s) selecionado(s)`
      }
    }
  }

  // Delete Media Confirmation
  const deleteButtons = document.querySelectorAll(".delete-media")

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      if (confirm("Tem certeza que deseja excluir este arquivo?")) {
        // Here you would typically send a request to the server to delete the file
        const mediaItem = this.closest(".media-item")
        mediaItem.remove()
        showSuccessMessage("Arquivo excluído com sucesso!")
      }
    })
  })

  // Form Submissions
  const contentForm = document.getElementById("contentForm")
  const workshopForm = document.getElementById("workshopForm")
  const uploadForm = document.getElementById("uploadForm")
  const faqForm = document.getElementById("faqForm")
  const teamForm = document.getElementById("teamForm")
  const servicesForm = document.getElementById("servicesForm")

  if (contentForm) {
    contentForm.addEventListener("submit", (e) => {
      e.preventDefault()
      // Here you would typically send the form data to the server
      showSuccessMessage("Conteúdo atualizado com sucesso!")
    })
  }

  if (workshopForm) {
    workshopForm.addEventListener("submit", (e) => {
      e.preventDefault()
      // Here you would typically send the form data to the server
      showSuccessMessage("Informações do workshop atualizadas com sucesso!")
    })
  }

  if (uploadForm) {
    uploadForm.addEventListener("submit", (e) => {
      e.preventDefault()
      const fileInput = document.getElementById("fileUpload")
      if (fileInput.files.length === 0) {
        alert("Por favor, selecione pelo menos um arquivo")
        return
      }
      // Here you would typically send the files to the server
      showSuccessMessage("Arquivos enviados com sucesso!")
    })
  }

  if (faqForm) {
    faqForm.addEventListener("submit", (e) => {
      e.preventDefault()
      showSuccessMessage("Perguntas frequentes atualizadas com sucesso!")
    })
  }

  if (teamForm) {
    teamForm.addEventListener("submit", (e) => {
      e.preventDefault()
      showSuccessMessage("Informações da equipe atualizadas com sucesso!")
    })
  }

  if (servicesForm) {
    servicesForm.addEventListener("submit", (e) => {
      e.preventDefault()
      showSuccessMessage("Serviços atualizados com sucesso!")
    })
  }

  // Add FAQ Item
  const addFaqButton = document.getElementById("addFaqButton")
  if (addFaqButton) {
    addFaqButton.addEventListener("click", () => {
      const faqContainer = document.querySelector(".faq-items-container")
      const faqCount = faqContainer.querySelectorAll(".collapsible-section").length + 1

      const newFaqItem = document.createElement("div")
      newFaqItem.className = "collapsible-section"
      newFaqItem.innerHTML = `
        <div class="collapsible-header">
          <h4>Pergunta ${faqCount}</h4>
          <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="collapsible-content">
          <div class="form-group">
            <label>Pergunta</label>
            <input type="text" name="faqQuestion${faqCount}" value="">
          </div>
          <div class="form-group">
            <label>Resposta</label>
            <textarea name="faqAnswer${faqCount}" rows="3"></textarea>
          </div>
          <button type="button" class="secondary-button remove-item">Remover</button>
        </div>
      `

      faqContainer.appendChild(newFaqItem)

      // Add event listener to the new collapsible header
      const newHeader = newFaqItem.querySelector(".collapsible-header")
      newHeader.addEventListener("click", () => {
        newFaqItem.classList.toggle("active")
        const content = newFaqItem.querySelector(".collapsible-content")
        content.classList.toggle("hidden", !newFaqItem.classList.contains("active"))
      })

      // Add event listener to the remove button
      const removeButton = newFaqItem.querySelector(".remove-item")
      removeButton.addEventListener("click", (e) => {
        e.stopPropagation()
        if (confirm("Tem certeza que deseja remover esta pergunta?")) {
          newFaqItem.remove()
          showSuccessMessage("Pergunta removida com sucesso!")
        }
      })

      // Open the new item
      newFaqItem.classList.add("active")
    })
  }

  // Add Team Member
  const addTeamButton = document.getElementById("addTeamButton")
  if (addTeamButton) {
    addTeamButton.addEventListener("click", () => {
      const teamContainer = document.querySelector(".team-items-container")
      const teamCount = teamContainer.querySelectorAll(".collapsible-section").length + 1

      const newTeamItem = document.createElement("div")
      newTeamItem.className = "collapsible-section"
      newTeamItem.innerHTML = `
        <div class="collapsible-header">
          <h4>Membro ${teamCount}</h4>
          <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="collapsible-content">
          <div class="form-group">
            <label>Nome</label>
            <input type="text" name="teamName${teamCount}" value="">
          </div>
          <div class="form-group">
            <label>Especialidade</label>
            <input type="text" name="teamSpecialty${teamCount}" value="">
          </div>
          <div class="form-group">
            <label>Currículo</label>
            <textarea name="teamBio${teamCount}" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label>Foto</label>
            <div class="image-preview" style="height: 150px; margin-bottom: 10px;">
              <img src="/placeholder.svg" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <input type="file" id="teamPhoto${teamCount}" name="teamPhoto${teamCount}" class="file-input" accept="image/*">
            <label for="teamPhoto${teamCount}" class="upload-button">Escolher Imagem</label>
          </div>
          <button type="button" class="secondary-button remove-item">Remover</button>
        </div>
      `

      teamContainer.appendChild(newTeamItem)

      // Add event listener to the new collapsible header
      const newHeader = newTeamItem.querySelector(".collapsible-header")
      newHeader.addEventListener("click", () => {
        newTeamItem.classList.toggle("active")
        const content = newTeamItem.querySelector(".collapsible-content")
        content.classList.toggle("hidden", !newTeamItem.classList.contains("active"))
      })

      // Add event listener to the file input
      const fileInput = newTeamItem.querySelector(".file-input")
      fileInput.addEventListener("change", function () {
        const file = this.files[0]
        if (file) {
          const preview = this.closest(".form-group").querySelector("img")
          const reader = new FileReader()
          reader.onload = (e) => {
            preview.src = e.target.result
          }
          reader.readAsDataURL(file)
        }
      })

      // Add event listener to the remove button
      const removeButton = newTeamItem.querySelector(".remove-item")
      removeButton.addEventListener("click", (e) => {
        e.stopPropagation()
        if (confirm("Tem certeza que deseja remover este membro?")) {
          newTeamItem.remove()
          showSuccessMessage("Membro removido com sucesso!")
        }
      })

      // Open the new item
      newTeamItem.classList.add("active")
    })
  }

  // Add Service
  const addServiceButton = document.getElementById("addServiceButton")
  if (addServiceButton) {
    addServiceButton.addEventListener("click", () => {
      const serviceContainer = document.querySelector(".service-items-container")
      const serviceCount = serviceContainer.querySelectorAll(".collapsible-section").length + 1

      const newServiceItem = document.createElement("div")
      newServiceItem.className = "collapsible-section"
      newServiceItem.innerHTML = `
        <div class="collapsible-header">
          <h4>Serviço ${serviceCount}</h4>
          <span class="toggle-icon"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div class="collapsible-content">
          <div class="form-group">
            <label>Título</label>
            <input type="text" name="serviceTitle${serviceCount}" value="">
          </div>
          <div class="form-group">
            <label>Descrição</label>
            <textarea name="serviceDesc${serviceCount}" rows="3"></textarea>
          </div>
          <button type="button" class="secondary-button remove-item">Remover</button>
        </div>
      `

      serviceContainer.appendChild(newServiceItem)

      // Add event listener to the new collapsible header
      const newHeader = newServiceItem.querySelector(".collapsible-header")
      newHeader.addEventListener("click", () => {
        newServiceItem.classList.toggle("active")
        const content = newServiceItem.querySelector(".collapsible-content")
        content.classList.toggle("hidden", !newServiceItem.classList.contains("active"))
      })

      // Add event listener to the remove button
      const removeButton = newServiceItem.querySelector(".remove-item")
      removeButton.addEventListener("click", (e) => {
        e.stopPropagation()
        if (confirm("Tem certeza que deseja remover este serviço?")) {
          newServiceItem.remove()
          showSuccessMessage("Serviço removido com sucesso!")
        }
      })

      // Open the new item
      newServiceItem.classList.add("active")
    })
  }

  // Initialize remove buttons for existing items
  const removeButtons = document.querySelectorAll(".remove-item")
  removeButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.stopPropagation()
      const item = this.closest(".collapsible-section")
      const itemType = item.getAttribute("data-type") || "item"

      if (confirm(`Tem certeza que deseja remover este ${itemType}?`)) {
        item.remove()
        showSuccessMessage(`${itemType.charAt(0).toUpperCase() + itemType.slice(1)} removido com sucesso!`)
      }
    })
  })

  function showSuccessMessage(message) {
    // Create a floating message
    const messageElement = document.createElement("div")
    messageElement.className = "success-message"
    messageElement.textContent = message

    // Style the message
    messageElement.style.position = "fixed"
    messageElement.style.bottom = "20px"
    messageElement.style.right = "20px"
    messageElement.style.backgroundColor = "var(--success)"
    messageElement.style.color = "white"
    messageElement.style.padding = "15px 20px"
    messageElement.style.borderRadius = "5px"
    messageElement.style.boxShadow = "0 3px 10px rgba(0, 0, 0, 0.2)"
    messageElement.style.zIndex = "1000"
    messageElement.style.opacity = "0"
    messageElement.style.transform = "translateY(20px)"
    messageElement.style.transition = "opacity 0.3s ease, transform 0.3s ease"

    // Add to document
    document.body.appendChild(messageElement)

    // Animate in
    setTimeout(() => {
      messageElement.style.opacity = "1"
      messageElement.style.transform = "translateY(0)"
    }, 10)

    // Remove after delay
    setTimeout(() => {
      messageElement.style.opacity = "0"
      messageElement.style.transform = "translateY(20px)"

      setTimeout(() => {
        document.body.removeChild(messageElement)
      }, 300)
    }, 3000)
  }
})

