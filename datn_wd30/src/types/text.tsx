// const imageProfile= async(image:any)=>{
    //       const render = new FileReader();
    //       render.readAsDataURL(image);
    //       const data = await new Promise((resolve,reject)=>{
    //         render.onload= () => resolve(render.result)
    //         render.onerror = errors => reject(errors)
    //       })

    //       return data
    // }
    // const handleImagePic = async(e:any)=>{
    //     const file = e.target.files[0];
    //     const image = await imageProfile(file);
    //     // console.log("image",image)
    //     setData((preve:any)=>{
    //         return {
    //             ...preve,
    //             profilePic : image
    //         }
    //     })
    // }