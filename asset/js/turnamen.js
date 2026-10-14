
      // Toggle Chat Logic
      const chatSidebar = document.getElementById('chat-sidebar');
      const btnCloseChat = document.getElementById('btn-close-chat');
      const btnOpenChat = document.getElementById('btn-open-chat');

      if(btnCloseChat && btnOpenChat) {
        btnCloseChat.addEventListener('click', () => {
          chatSidebar.classList.add('chat-hidden');
          btnOpenChat.classList.remove('hidden');
        });

        btnOpenChat.addEventListener('click', () => {
          chatSidebar.classList.remove('chat-hidden');
          btnOpenChat.classList.add('hidden');
        });
      }

      // Mobile Menu
      const menuBtn = document.querySelector("#menu-btn");
      if(menuBtn) {
        menuBtn.addEventListener("click", () => {
          document.querySelector("#desktop-nav").classList.toggle("hidden");
          document.querySelector("#desktop-nav").classList.toggle("flex");
          document.querySelector("#desktop-nav").classList.toggle("flex-col");
          document.querySelector("#desktop-nav").classList.toggle("absolute");
          document.querySelector("#desktop-nav").classList.toggle("top-[76px]");
          document.querySelector("#desktop-nav").classList.toggle("left-0");
          document.querySelector("#desktop-nav").classList.toggle("w-full");
          document.querySelector("#desktop-nav").classList.toggle("bg-[#080f18]");
          document.querySelector("#desktop-nav").classList.toggle("p-4");
          document.querySelector("#desktop-nav").classList.toggle("z-40");
        });
      }

      // Chat Tabs
      document.querySelectorAll(".chat-tab").forEach((tab) => {
        tab.addEventListener("click", () => {
          document.querySelectorAll(".chat-tab").forEach((item) => {
            item.classList.remove("border-red", "text-red", "bg-card");
            item.classList.add("border-transparent", "text-muted");
          });
          tab.classList.remove("border-transparent", "text-muted");
          tab.classList.add("border-red", "text-red", "bg-card");
        });
      });

      // Chat Form Submit
      const chatForm = document.querySelector("#chat-form");
      if(chatForm) {
        chatForm.addEventListener("submit", (event) => {
          event.preventDefault();
          const input = document.querySelector("#chat-input");
          const val = input.value.trim();
          if (!val) return;
          
          const bubble = document.createElement("div");
          bubble.className = "flex flex-col items-end self-end max-w-[85%] mt-2";
          bubble.innerHTML = `
            <div class="rounded-2xl rounded-br-sm bg-red px-3.5 py-2 text-[12px] leading-relaxed text-white shadow-sm shadow-red/10">
              ${val}
            </div>
            <span class="mt-1 mr-1 text-[9px] text-muted text-right">Barusan</span>
          `;
          
          const chatContainer = document.querySelector("#chat-content .flex-1.flex.flex-col.justify-end");
          chatContainer.appendChild(bubble);
          
          const wrapper = document.querySelector("#chat-content");
          wrapper.scrollTop = wrapper.scrollHeight;
          
          input.value = "";
        });
      }

