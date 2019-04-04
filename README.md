<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Wish List By David Brihuega

Aplicación web que consiste en una lista de deseos para que el usuario agregue, elimine, compre o edite sus deseos que vienen mostrados en una lista la cual se enfoca en la usabilidad , la experiencia del usuario y performance.
La aplicación fue creada bajo la metodología mobile first y los requerimientos de los usuarios son: 

-Los deseos constan de un nombre, descripción, una fecha de modificación/creación y una fotografía.
-El usuario tiene un límite máximo como presupuesto para comprar los artículos de su lista de deseos.
-El usuario puede crear los deseos que necesite los cuales puede visualizar en una lista.
-El usuario puede marcar como comprado un deseo.
-El usuario puede ver en una lista los deseos que ya ha comprado y otra lista de los que le faltan por comprar
-El usuario puede ver un distintivo de los artículos ya comprados y no podrá volver a comprarlos.
-El usuario puede modificar los deseos que aun no ha comprado.
-El usuario puede eliminar cualquier deseo ya sea comprado o no.
-El usuario recibe un correo electrónico cada que se cree un nuevo deseo o sea comprado
-El usuario puede ver en grande la imagen del deseo
-El usuario puede ver hace cuanto tiempo fue publicado o actualizado el deseo 
-El usuario no puede superar el límite de su presupuesto establecido.
-Si el usuario desea comprar más artículos puede incrementar su presupuesto
-El usuario cuando compra un artículo su presupuesto irá bajando con cada compra.
-El usuario puede consultar cuanto ha gastado y cual es su presupuesto actual
-El usuario puede buscar en su lista un deseo en particular por su nombre o descripción.

## Extras 

-Para que sea visible el envió de correos la aplicación cuenta con un login y un registro.
-Al comprar un articulo se efectúa una transacción para así asegurar que se reste del presupuesto correctamente y el deseo cambie de estatus.
-Cuando se crea un deseo se crea un thumbnail para visualizar en el listado y una imagen para ver en grande con sus medidas proporcionales para evitar imagenes pesadas.

## Algunas Herramientas para el desarrollo 

-Laravel
-Vuejs
-Css
-Mysql
-Js
-Jquery
-Bootstrap
-Html5
-Eloquent

## Notas 

-El usuario comienza sin deseos y se tienen que agregar algunos para así poder visualizarlos.
-Laravel no fue utilizado como un api puesto que vuejs ya viene integrado y mejorar el desempeño de la aplicación.
*En el desarrollo de REST API ya tengo experiencia puesto que en la aplicación que estaba trabajando funcionaba a través de un api que funcionaba con passport para comunicarse con el frontend desarrollado en REACT, el cual desarrolle en su totalidad.

## Demo

La aplicación esta adaptada a los dispositivos móviles bajo la metodología mobile first, para una mejor experiencia :

-En PC entrar a : 
http://www.responsinator.com/?url=wishlist.wmyweb.site%2F%23

-Dispositivo móvil : 
http://wishlist.wmyweb.site
