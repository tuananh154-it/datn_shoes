import React, { useEffect, useState } from "react";
import { Contacts, getContact } from "../services/contacts";
import toast from "react-hot-toast";

const Contact = () => {
    const [contact, setContact] = useState<Contacts[]>([]);
    useEffect(() => {
        getContact()
            .then(({ data }) => {
                setContact(data);
                console.log(data);
                toast.success("Lấy thông tin liên hệ thành công");
            })
            .catch((e) => toast.error("Error: " + e.message));
    }, []);

    return (
        <>
            <div className="menu_overlay"></div>
            <div className="main_section">
                <section className="breadcrumb_section nav">
                    <div className="container">
                        <nav aria-label="breadcrumb">
                            <ol className="breadcrumb">
                                <li className="breadcrumb-item text-capitalize">
                                    <a href="earthyellow.html">Home</a> <i className="flaticon-arrows-4"></i>
                                </li>
                                <li className="breadcrumb-item active text-capitalize">Liên hệ</li>
                            </ol>
                        </nav>
                        <h1 className="title_h1 font-weight-normal text-capitalize">Thông tin liên hệ</h1>
                    </div>
                </section>

                <section className="login_section padding-top-text-60 padding-bottom-60">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-6 border-right">
                                <div className="login_form">
                                    {contact.map((c, index) => (
                                        <form action="">
                                            <div className="form-group">
                                                <label htmlFor={`name-${index}`} className="title_h5">Name</label>
                                                <input
                                                    type="text"
                                                    className="form-control"
                                                    id={`name-${index}`}
                                                    name="name"
                                                    value={c.name || ""}
                                                    readOnly
                                                />
                                            </div>
                                            <div className="form-group">
                                                <label htmlFor={`email-${index}`} className="title_h5">Email</label>
                                                <input
                                                    type="email"
                                                    className="form-control"
                                                    id={`email-${index}`}
                                                    name="email"
                                                    value={c.email || ""}
                                                    readOnly
                                                />
                                            </div>
                                            <div className="form-group">
                                                <label htmlFor={`phone-${index}`} className="title_h5">Phone</label>
                                                <input
                                                    type="text"
                                                    className="form-control"
                                                    id={`phone-${index}`}
                                                    name="phone"
                                                    value={c.phone_number || ""}
                                                    readOnly
                                                />
                                            </div>
                                        </form>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </>
    );
};

export default Contact;
