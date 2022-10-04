# Chat

[![laravel](https://img.shields.io/badge/Laravel-v9.2-ff1e12?logo=laravel)](https://laravel.com/docs/9.x)
[![vue](https://img.shields.io/badge/Vue.js-v2.6.12-33b378?logo=vuedotjs)](https://v2.vuejs.org/)
[![vuetify](https://img.shields.io/badge/Vuex-v3.6.2-33b378)](https://v3.vuex.vuejs.org/)
[![bootstrap](https://img.shields.io/badge/Bootstrap-v5.1.3-6a2ff9?logo=bootstrap)](https://getbootstrap.com/docs/5.1/getting-started/introduction/)
[![axios](https://img.shields.io/badge/Axios-v0.25-4e25e3?logo=axios)](https://axios-http.com/)

## About project

Chat with the ability to write private messages as well as create groups. 

All database queries are done with Axios. 

The main data is in the Vuex repository, so Vue can retrieve it using nothing more than getters and setters to that repository. 

The data is displayed in real-time with a delay of a few seconds. 

There are three resource models (user, chat, message), with the implementation of the Many To Many relationship.

## UI
![sing-up](https://user-images.githubusercontent.com/28041087/193830209-76c9ecbb-2570-4a4f-bc31-4e5887b682d8.png)
![login](https://user-images.githubusercontent.com/28041087/193830399-6749b0ce-88c8-44fa-9648-e3de803f9fb6.png)
![index](https://user-images.githubusercontent.com/28041087/193849011-f51d4278-f682-44cb-9699-2af2ce458bf9.png)
![chat-add](https://user-images.githubusercontent.com/28041087/193849146-f79404d8-e9c6-4603-a4b8-47fc2eeaf8e2.png)
![chat-edit](https://user-images.githubusercontent.com/28041087/193849201-d4d37a6b-9e64-4f1f-aa45-3b967e4ad6ec.png)
![admin](https://user-images.githubusercontent.com/28041087/193849272-bbff7761-7018-4311-8845-86da80594d64.png)
