//
//  InformativeViewController.swift
//  WeatherToWear
//
//  Created by Kodeeshwari Solanki on 2023-04-12.
//

import UIKit

class RegisterViewController: UIViewController, UIPickerViewDelegate, UIPickerViewDataSource, UITextFieldDelegate  {
    
    @IBOutlet weak var txtFullName: UITextField!
    
    @IBOutlet weak var txtEmail: UITextField!
    
    @IBOutlet weak var txtPhone: UITextField!
    
    @IBOutlet weak var txtGender: UITextField!
    
    @IBOutlet weak var txtCity: UITextField!
    
    @IBOutlet weak var txtPassword: UITextField!
    
    @IBOutlet weak var btnRegister: UIButton!
    
    var selectedGender: String?
    var genderIndex: String?
    var genderList = ["Male", "Female", "Other"]
    
    var responsecode : Int = 0
    
    override func viewDidLoad() {
        super.viewDidLoad()
        createPickerView()
        dismissPickerView()
    }
    
    func numberOfComponents(in pickerView: UIPickerView) -> Int {
        return 1
    }
    
    func pickerView(_ pickerView: UIPickerView, numberOfRowsInComponent component: Int) -> Int {
        return genderList.count
    }
    
    func pickerView(_ pickerView: UIPickerView, titleForRow row: Int, forComponent component: Int) -> String? {
        return genderList[row]
    }
    
    func pickerView(_ pickerView: UIPickerView, didSelectRow row: Int, inComponent component: Int) {
        selectedGender = genderList[row] // selected item
        genderIndex = String(row)
        txtGender.text = selectedGender
    }
    
    func createPickerView() {
        let pickerView = UIPickerView()
        pickerView.delegate = self
        txtGender.inputView = pickerView
    }
    
    func dismissPickerView() {
        let toolBar = UIToolbar()
        toolBar.sizeToFit()
        let button = UIBarButtonItem(title: "Done", style: .plain, target: self, action: #selector(self.action))
        toolBar.setItems([button], animated: true)
        toolBar.isUserInteractionEnabled = true
        txtGender.inputAccessoryView = toolBar
    }
    
    @objc func action() {
        view.endEditing(true)
    }
    
    @IBAction func btnRegisterTouchUpInside(_ sender: Any) {
        
        guard let fullname = txtFullName.text,
              let email = txtEmail.text,
              let phone = txtPhone.text,
              let gender = genderIndex,
              let city = txtCity.text,
              let password = txtPassword.text else {
            // handle missing fields here
            Toast.ok(view: self, title: "Error", message: "No feild should be empty")
            return
        }
        let newuser = UserModel(fullname: fullname, email: email, phone: phone, gender: gender, city: city, password: password)
        
        
        
        
            UserAuthAPI.signUp(fullname: newuser.fullName, email: newuser.email, phone: newuser.phone, gender:newuser.gender, city: newuser.city, password: newuser.password, successHandler: {(httpStatusCode,response) in
                DispatchQueue.main.async {
                    if httpStatusCode==200{
                        let message = response["message"] as? String ?? "Unknown response"
//                        Toast.ok(view: self, title: "Response", message: message+"\(Thread.isMainThread)")
                        print(Thread.isMainThread)
                        
                            self.performSegue(withIdentifier: Segue.toLoginViewController, sender: self)
                    }
                    else{
                        Toast.ok(view: self, title: "Response", message: "\(httpStatusCode)")
                    }
                }
            }, failHandler: {(httpStatusCode,errorMessage)in
                print("HTTP Status Code: \(httpStatusCode)")
                print("Error Message: \(errorMessage)")
                DispatchQueue.main.async {
                    Toast.ok(view: self, title: "Error: \(httpStatusCode)", message: "\(errorMessage)")
                }

            })
        
        
    }
    
    
    @IBAction func btnLoginTouchUpInside(_ sender: Any) {
        performSegue(withIdentifier: Segue.toLoginViewController, sender: nil)
    }
}
