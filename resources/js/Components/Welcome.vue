<template>
    <div>
        <div class="p-3 bg-white border-b border-gray-200 flex justify-between">
            <h1 class="text-2xl font-medium text-gray-900">
                Google PaLM API :Chat
            </h1>
            <button class="rounded-md bg-red-100 p-2 drop-shadow-xl" @click="clearChat" v-if="chatData.length > 0">Clear</button>

        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1  gap-6 lg:gap-8">
            <!-- This is an example component -->
            <div class="container mx-auto shadow-lg rounded-lg ">
                <!-- Chatting -->
                <div id="chatDiv"  class="justify-between bg-white ">

                    <!-- message -->
                    <div ref="chatDivRef" class="w-full px-5 flex flex-col justify-between text-[14px] h-[550px] overflow-auto custom-scrollbar" >
                        <div class="flex flex-col mt-5">
                            <div class="flex justify-start mb-4">
                                <img src="https://images.pexels.com/photos/8566472/pexels-photo-8566472.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                                    class="object-cover h-8 w-8 rounded-full" alt="" />
                                <div
                                    class="ml-2 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white">
                                    Hey, I am Inspizy Bot.
                                </div>
                            </div>
                            <div v-for="(message, index) in chatData" :key="index">
                                <div v-if="index % 2 === 0" class="flex justify-end mb-4">
                                    <div
                                        class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white">
                                        {{ message.content }}
                                    </div>
                                    <img src="https://ui-avatars.com/api/?name=M+E&color=7F9CF5&background=EBF4FF"
                                        class="object-cover h-8 w-8 rounded-full" alt="" />
                                </div>
                                <div v-else class="flex justify-start mb-4">
                                    <img src="https://images.pexels.com/photos/8566472/pexels-photo-8566472.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                                        class="object-cover h-8 w-8 rounded-full" alt="" />
                                    <div
                                        class="ml-2 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white">
                                        {{ message.content }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-start mb-4" v-if="fetchingOn">
                                <img src="https://images.pexels.com/photos/8566472/pexels-photo-8566472.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1"
                                    class="object-cover h-8 w-8 rounded-full" alt="" />
                                <div
                                    class="ml-2 py-3 px-4 bg-gray-400 rounded-br-3xl rounded-tr-3xl rounded-tl-xl text-white">
                                    <div class="spinner"></div>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                    <div class="p-5">
                            <input class="w-full bg-gray-300 py-3 px-3 rounded-xl" type="text" v-model="newMessage"
                                placeholder="type your message here..." @keydown.enter="sendMessageToPalm" />
                        </div>
                    <!-- end message -->

                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
    import {
        ref,
        reactive,
        onMounted
    } from 'vue';
    import axios from 'axios';
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
    const newMessage = ref('');
    const chatData = ref([
    ]);
    const chatDivRef = ref(null);
    const fetchingOn = ref(false);
    const sendMessageToPalm =async()=>{
        if(fetchingOn.value){
            return 0;
        }
        if (newMessage.value.trim() !== "") {
        chatData.value.push({ content: newMessage.value }); // Add new message to the chatData array
        newMessage.value = ""; // Clear the input field
       
      }else{
        alert('Please Write Something!');
        return 0;
      }
      fetchingOn.value= true;
      scrollChatToBottom();
      await  axios.post('google-chat-req',{
            messages:chatData.value
        }).then(res=>{
            if(res.data.status){
                if(res.data.chat.candidates){
                    chatData.value.push({ content: res.data.chat.candidates[0].content }); // Add new message to the chatData array
              localStorage.setItem('chatData', JSON.stringify(chatData.value));
              scrollChatToBottom();
                }else{
                chatData.value.pop();
                }
              
            }else{
                chatData.value.pop();
            }
            fetchingOn.value= false;
        });

    };
    const scrollChatToBottom =() => {
      setTimeout(() => {
        chatDivRef.value.scrollTop = chatDivRef.value.scrollHeight;
      }, 100);
    };
    const clearChat = ()=>{
        chatData.value = [];
        localStorage.removeItem('chatData');
        fetchingOn.value= false;

    }
    onMounted(() => {
        const storedChatData = localStorage.getItem('chatData');
      if (storedChatData) {
        chatData.value = JSON.parse(storedChatData);
      }
      scrollChatToBottom();
    });

</script>
<style>
 /* Custom scrollbar styling */
 .custom-scrollbar::-webkit-scrollbar {
    width: 8px;
  }

  .custom-scrollbar::-webkit-scrollbar-track {
    background-color: #f1f1f1;
    border-radius: 4px;
  }

  .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
  }

  .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #555;
  }
.spinner {
   width: 24px;
   height: 24px;
   display: grid;
}

.spinner::before,
.spinner::after {
   content: "";
   grid-area: 1/1;
   background: var(--c) 50%  0, 
        var(--c) 50%  100%, 
        var(--c) 100% 50%, 
        var(--c) 0    50%;
   background-size: 5.8px 5.8px;
   background-repeat: no-repeat;
   animation: spinner-3hs4a3 1s infinite;
}

.spinner::before {
   --c: radial-gradient(farthest-side,#ae14e6 92%,#0000);
   margin: 1.9px;
   background-size: 3.8px 3.8px;
   animation-timing-function: linear;
}

.spinner::after {
   --c: radial-gradient(farthest-side,#ae14e6 92%,#0000);
}

@keyframes spinner-3hs4a3 {
   100% {
      transform: rotate(.5turn);
   }
}
</style>

