//
//  UserModel.swift
//  WeatherToWear
//
//  Created by Kodeeshwari Solanki on 2023-04-15.
//

import Foundation

class UserModel : Codable{
    
    var fullName : String?
    var email : String?
    var phone : String?
    var gender : String?
    var city : String?
    var password : String?
    
    init(fullname : String,email : String,
         phone : String, gender : String, city : String,
         password : String) {
        self.fullName = fullname
        self.email = email
        self.phone = phone
        self.gender = gender
        self.city = city
        self.password = password
    }
    
    static func decode( json : [String:Any]!) -> UserModel? {

        let decoder = JSONDecoder()
        do{
            let data = try JSONSerialization.data(withJSONObject: json, options: .prettyPrinted)
            let object = try decoder.decode(UserModel.self, from: data)
            return object
        } catch{
        }
        return nil
    }
}
