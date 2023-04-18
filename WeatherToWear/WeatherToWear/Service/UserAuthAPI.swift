//
//  UserAuthAPI.swift
//  WeatherToWear
//
//  Created by Kodeeshwari Solanki on 2023-04-15.
//

import Foundation

class UserAuthAPI{
    
    static let baseURL = "http://localhost/weathertowearAPI/"
    
    static func signIn( email : String,
                        password : String,
                        successHandler: @escaping (_ httpStatusCode : Int, _ response : [String: Any]) -> Void,
                        failHandler : @escaping (_ httpStatusCode : Int, _ errorMessage: String) -> Void) {
        
        let endPoint = "login.php"
        
        // encode the username and password for Basic Auth
        let credentials = "\(email):\(password)"
        let base64Credentials = Data(credentials.utf8).base64EncodedString()
        let authString = "Basic \(base64Credentials)"
        
        let header : [String:String] = ["Authorization":authString]
        
        var payload : [String:Any] = ["email": email, "password" : password]
        
        API.call(baseURL: baseURL, endPoint: endPoint, method: "POST", header: header, payload: payload, successHandler: successHandler, failHandler: failHandler)
        
    }
    
    static func signUp( fullname : String?,
                        email : String?,
                        phone:String?,
                        gender: String?,
                        city: String?,
                        password:String?,
                        successHandler: @escaping (_ httpStatusCode : Int, _ response : [String: Any]) -> Void,
                        failHandler : @escaping (_ httpStatusCode : Int, _ errorMessage: String) -> Void) {
        
        let endPoint = "signUp.php"
        
        let header : [String:String] = [:]
        
        let payload : [String:Any] = ["fullName": fullname!,"email": email!,"phone": phone!,"gender": gender!, "city": city!, "password":password!]
        
        API.call(baseURL: baseURL, endPoint: endPoint, method: "POST", header: header, payload: payload, successHandler: successHandler, failHandler: failHandler)
    }

}
