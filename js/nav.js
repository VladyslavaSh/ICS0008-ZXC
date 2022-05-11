let listElements = document.getElementById("nav-list").children;
for (i of listElements) {
  let link = i.getElementsByTagName("a")[0];
  switch (document.title) {
    case "Home - ZXC":
      if (link.innerHTML == "Home") {
        link.classList.add("nav-current");
      }
      break;
    case "About us - ZXC":
      if (link.innerHTML == "About") {
        link.classList.add("nav-current");
      }
      break;
    case "Catalog - ZXC":
      if (link.innerHTML == "Order") {
        link.classList.add("nav-current");
      }
      break;
    case "Policy - ZXC":
      if (link.innerHTML == "Policy") {
        link.classList.add("nav-current");
      }
      break;
    case "Support - ZXC":
      if (link.innerHTML == "Support") {
        link.classList.add("nav-current");
      }
       break;
    case "Login - ZXC":
      if (link.innerHTML == "Log In") {
        link.classList.add("nav-current");
      }
      break;
    case "Profile - ZXC":
      if (link.id == "avatar_link") {
        link.classList.add("nav-current");
      }
      break;
    default:
    console.log(i);
    console.log(link);
  }
}
